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
use App\Api\Processor\CreateContentProcessor;
use App\Api\Resource\CreateContent;
use App\Doctrine\Enum\RoleEnum;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
#[ApiResource(
    operations: [
        new GetCollection(normalizationContext: ['groups' => ['content:read']]),
        new Get(
            uriTemplate: '/content/{slug}',
            uriVariables: ['slug'],
            normalizationContext: ['groups' => ['content:read', 'content:read-item']],
        ),
        new Post(
            normalizationContext: ['groups' => ['content:read']],
            denormalizationContext: ['groups' => ['content:create']],
            security: RoleEnum::IS_GRANTED_ADMIN,
            input: CreateContent::class,
            processor: CreateContentProcessor::class
        ),
        new Patch(
            uriVariables: ['slug'],
            normalizationContext: ['groups' => ['content:read']],
            denormalizationContext: ['groups' => ['content:update']],
            security: RoleEnum::IS_ADMIN_OR_AUTHOR_OBJECT
        ),
        new Delete(uriVariables: ['slug'], security: RoleEnum::IS_ADMIN_OR_AUTHOR_OBJECT),
    ],
    order: ['createdAt' => 'DESC']
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial', 'author.email' => 'exact'])]
class Content
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public string $title;

    #[ORM\ManyToOne(targetEntity: Upload::class)]
    #[ORM\JoinColumn(nullable: true)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public ?Upload $cover = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public ?string $metaTitle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public ?string $metaDescription = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    #[Groups(['content:read', 'content:create', 'content:update', 'content:read-item'])]
    public ?string $content = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Assert\Length(min: 1, max: 255)]
    #[ApiProperty(writable: false)]
    #[Groups(['content:read'])]
    public ?string $slug = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public ?string $description = null;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    #[Groups(['content:read', 'content:create', 'content:update'])]
    public ?array $tags = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'author_id', nullable: true)]
    #[ApiProperty(writable: false)]
    #[Groups(['content:read'])]
    public ?User $author = null;
}
