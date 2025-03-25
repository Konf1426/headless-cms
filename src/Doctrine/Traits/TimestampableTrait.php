<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableTrait
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Groups(['comment:read'])]
    #[ApiProperty(readable: true, writable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['comment:read'])]
    #[ApiProperty(readable: true, writable: false)]
    private ?\DateTime $updatedAt = null;

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?DateTimeImmutable $createdAt = null): void
    {
        $this->createdAt = $createdAt ?? new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt = null): void
    {
        $this->updatedAt = $updatedAt ?? new \DateTime();
    }
}
