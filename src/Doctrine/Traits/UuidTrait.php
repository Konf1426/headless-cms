<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    #[ApiProperty(writable: false, identifier: true)]
    #[Groups(['user:read', 'content:read-item', 'comment:read'])]
    public ?Uuid $id;

    public function __construct()
    {
        $this->id ??= Uuid::v4();
    }

    /**
     * @return Uuid|null
     */
    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
