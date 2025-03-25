<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateCommentProcessor;
use App\Api\Resource\CreateComment;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Enum\RoleEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::COMMENT)]
#[ApiResource(
    operations: [
        new GetCollection(
            normalizationContext: ['groups' => ['comment:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Get(
            normalizationContext: ['groups' => ['comment:read']],
            security: "is_granted('ROLE_USER')"
        ),
        new Post(
            normalizationContext: ['groups' => ['comment:read']],
            security: "is_granted('ROLE_USER')",
            input: CreateComment::class,
            processor: CreateCommentProcessor::class
        ),
        new Patch(
            normalizationContext: ['groups' => ['comment:read']],
            denormalizationContext: ['groups' => ['comment:update']],
            security: "is_granted('ROLE_USER') and object.author == user"
        ),
        new Delete(security: "is_granted('ROLE_ADMIN') or (is_granted('ROLE_USER') and object.author == user)"),
    ],
    order: ['createdAt' => 'DESC']
)]
#[ApiFilter(SearchFilter::class, properties: ['content.slug' => 'exact'])]
class Comment
{
    use UuidTrait;
    use TimestampableTrait; // ✅ Utilisation du trait sans redéfinition

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups(['comment:read', 'comment:update'])]
    public string $message;

    #[ORM\ManyToOne(targetEntity: Content::class)]
    #[Groups(['comment:read'])]
    public ?Content $content = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ApiProperty(readable: true, writable: false)]
    #[Groups(['comment:read'])]
    public ?User $author = null;

}
