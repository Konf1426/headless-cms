<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateContentProcessor;
use App\Api\Resource\CreateContent;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::CONTENT)]
#[ApiResource(order: ['createdAt' => 'DESC'])]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Get(uriTemplate: '/content/{slug}', uriVariables: ['slug'], security: "is_granted('ROLE_USER')")]
#[Put(uriVariables: ['slug'], security: "is_granted('ROLE_ADMIN') or object.author == user")]
#[Post(security: "is_granted('ROLE_ADMIN')", input: CreateContent::class, processor: CreateContentProcessor::class)]
#[Delete(uriVariables: ['slug'], security: "is_granted('ROLE_ADMIN')")]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
class Content
{
    use UuidTrait, TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    public string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Length(min: 1, max: 255)]
    public ?string $cover = null;

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
    public ?string $slug = null;

    #[ORM\Column(type: 'json', nullable: true)]
    public ?array $tags = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'author_id', nullable: true)]
    public ?User $author = null;

    public function __construct()
    {
        $this->defineId();
    }
}