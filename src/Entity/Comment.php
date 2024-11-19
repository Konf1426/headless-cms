<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Doctrine\Traits\TimestampableEntity;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: 'comment')]
#[ApiResource(order: ['createdAt' => 'DESC'])]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Get(security: "is_granted('ROLE_USER')")]
#[Post(security: "is_granted('ROLE_USER')")]
#[Delete(security: "is_granted('ROLE_ADMIN')")]
class Comment
{
    use UuidTrait, TimestampableEntity;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    public string $content;

    #[ORM\ManyToOne(targetEntity: User::class)]
    public ?User $author = null;

    public function __construct()
    {
        $this->defineId();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
