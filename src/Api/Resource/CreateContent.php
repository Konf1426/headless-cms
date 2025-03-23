<?php

declare(strict_types=1);

namespace App\Api\Resource;

class CreateContent
{
    /**
     * @var string
     */
    public string $title;

    /**
     * @var string|null
     */
    public ?string $content = null;

    /**
     * @var string|null
     */
    public ?string $metaDescription = null;

    /**
     * @var string[]
     */
    public ?array $tags = null;

    /**
     * @var string|null
     */
    public ?string $cover = null;
}
