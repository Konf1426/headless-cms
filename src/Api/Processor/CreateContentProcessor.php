<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final readonly class CreateContentProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private ValidatorInterface $validator,
        private Security $security
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): ?Content {
        /** @var ?User $user */
        $user = $this->security->getUser();
        $content = new Content();
        $content->title = $data->title;
        $content->content = $data->content;
        $content->tags = $data->tags;
        $content->author = $user;

        $violations = $this->validator->validate($content);
        if ($violations->count() > 0) {
            throw new InvalidArgumentException((string) $violations->get(0)->getMessage());
        }

        $this->em->persist($content);
        $this->em->flush();
        return $content;
    }
}
