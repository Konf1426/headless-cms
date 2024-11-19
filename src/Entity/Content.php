<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Doctrine\Traits\UuidTrait;
use App\Doctrine\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'content')]
#[ApiResource(order: ['createdAt' => 'DESC'])]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Get(security: "is_granted('ROLE_USER')")]
#[Put(security: "is_granted('ROLE_ADMIN')")]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
class Content
{
    use UuidTrait, TimestampableEntity;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank]
    public string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $cover = null;

    #[ORM\Column(type: 'string', length: 255)]
    public ?string $metaTitle = null;

    #[ORM\Column(type: 'text', nullable: true)]
    public ?string $metaDescription = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public ?string $content = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Gedmo\Slug(fields: ['title'])]
    public ?string $slug = null;

    #[ORM\Column(type: 'json')]
    public ?array $tags = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name:'author_id')]
    public ?User $author = null;

    public function __construct()
    {
        $this->defineId();
    }
}