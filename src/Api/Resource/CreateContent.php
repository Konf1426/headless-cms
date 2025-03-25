<?php

declare(strict_types=1);

namespace App\Api\Resource;

use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints as Assert;

class CreateContent
{
    #[Assert\NotBlank]
    #[Groups(['content:create'])]
    public string $title = '';

    #[Groups(['content:create'])]
    public ?string $metaTitle = null;

    #[Groups(['content:create'])]
    public ?string $metaDescription = null;

    #[Assert\NotBlank]
    #[Groups(['content:create'])]
    public string $content = '';

    #[Groups(['content:create'])]
    public ?string $description = null;

    #[Groups(['content:create'])]
    public ?array $tags = [];

    #[Groups(['content:create'])]
    public ?string $author = null; // Ex: /api/users/uuid
}
