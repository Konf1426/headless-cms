<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use App\Entity\Upload;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements ProcessorInterface<Content, Operation>
 */
final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private Security $security
    ) {}

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return object
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): object {
        /** @var ?User $user */
        $user = $this->security->getUser();

        $content = new Content();
        $content->title = $data->title;
        $content->content = $data->content;
        $content->metaDescription = $data->metaDescription;
        $content->tags = $data->tags;

        // ✅ Ajout des champs manquants
        $content->metaTitle = $data->metaTitle ?? null;
        $content->metaDescription = $data->metaDescription ?? null;
        $content->description = $data->description ?? null;

        $content->author = $user;
        $content->setCreatedAt();
        $content->setUpdatedAt();

        if ($data->cover) {
            $upload = $this->em->getRepository(Upload::class)->find($data->cover);
            if (!$upload) {
                throw new InvalidArgumentException('Upload not found');
            }
            $content->cover = $upload;
        }

        $violations = $this->validator->validate($content);
        if ($violations->count() > 0) {
            throw new InvalidArgumentException((string) $violations->get(0)->getMessage());
        }

        $this->em->persist($content);
        $this->em->flush();

        return $content;
    }
}
