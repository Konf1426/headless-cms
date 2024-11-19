<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

trait UuidTrait
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: "NONE")]
    public ?Uuid $id;

    public function defineId(): void
    {
        $this->id ??= Uuid::v4();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }
}
