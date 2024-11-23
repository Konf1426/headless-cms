<?php

declare(strict_types=1);

namespace App\Api\Resource;

use Symfony\Component\Validator\Constraints as Assert;

class CreateComment
{
    /**
     * @var string
     */
    #[Assert\NotBlank]
    public string $message;

    /**
     * @var string
     */
    #[Assert\Uuid]
    public string $contentId;
}
