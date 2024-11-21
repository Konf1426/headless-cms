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
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateContentProcessor;
use App\Api\Resource\CreateContent;
use App\Doctrine\Enum\RoleEnum;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(uriTemplate: '/content/{slug}', uriVariables: ['slug']),
        new Post(
            security: RoleEnum::IS_GRANTED_ADMIN,
            input: CreateContent::class,
            processor: CreateContentProcessor::class
        ),
        new Put(
            uriVariables: ['slug'],
            security: RoleEnum::IS_ADMIN_OR_AUTHOR_OBJECT,
            input: CreateContent::class,
            processor: CreateContentProcessor::class
        ),
        new Delete(uriVariables: ['slug'], security: RoleEnum::IS_ADMIN_OR_AUTHOR_OBJECT),
    ],
    order: ['createdAt' => 'DESC']
)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Content
{
    use UuidTrait;
    use TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[ORM\ManyToOne(targetEntity: Upload::class)]
    public ?Upload $cover = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $metaTitle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $metaDescription = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public ?string $content = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    #[Assert\Length(min: 1, max: 255)]
    #[ApiProperty(writable: false)]
    public ?string $slug = null;

    /**
     * @var string[]|null
     */
    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $tags = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'author_id', nullable: true)]
    #[ApiProperty(writable: false)]
    public ?User $author = null;
}
