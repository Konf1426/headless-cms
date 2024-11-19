<?php

declare(strict_types=1);

namespace App\Doctrine\Traits;

use DateTime;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TimestampableEntity
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false, options: ['default' => 'CURRENT_TIMESTAMP'], generated: 'INSERT')]
    public ?DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, insertable: false, updatable: false, columnDefinition: 'DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP', generated: 'ALWAYS')]
    public ?DateTime $updatedAt = null;
}
