<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Api\Resource\CreateComment;
use App\Entity\Comment;
use App\Entity\Content;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @implements ProcessorInterface<Comment, Operation>
 */
final readonly class CreateCommentProcessor implements ProcessorInterface
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

        /** @var CreateComment $data */
        $content = $this->em->getRepository(Content::class)->findOneBy(['slug' => $data->contentSlug]);

        if (!$content) {
            throw new InvalidArgumentException('Content not found');
        }

        $comment = new Comment();
        $comment->message = $data->message;
        $comment->content = $content;
        $comment->author = $user;
        $comment->setCreatedAt();
        $comment->setUpdatedAt();

        $violations = $this->validator->validate($content);
        if ($violations->count() > 0) {
            throw new InvalidArgumentException((string) $violations->get(0)->getMessage());
        }
        

        $this->em->persist($comment);
        $this->em->flush();
        return $comment;
    }
}
