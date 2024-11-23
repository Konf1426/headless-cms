<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use ApiPlatform\Metadata\ApiProperty;
use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

trait TimestampableTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false, options: ['default' => 'CURRENT_TIMESTAMP'], generated: 'INSERT')]
    #[Groups(['comment:read'])]
    #[ApiProperty(readable: true, writable: false)]
    public ?DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false, columnDefinition: 'DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP', generated: 'ALWAYS')]
    #[Groups(['comment:read'])]
    #[ApiProperty(readable: true, writable: false)]
    public ?DateTime $updatedAt = null;
}
