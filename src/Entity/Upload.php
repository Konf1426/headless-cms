<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use App\Api\Action\UploadAction;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\TimestampableTrait;
use App\Doctrine\Traits\UuidTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: TableEnum::UPLOAD)]
#[Get]
#[Post(controller: UploadAction::class, deserialize: false)]
class Upload
{
    use UuidTrait, TimestampableTrait;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $filename = null;

    #[ORM\Column]
    public ?string $path = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    public ?string $mimeType = null;

    public function __construct()
    {
        $this->defineId();
    }
}