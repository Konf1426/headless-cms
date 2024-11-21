<?php

declare(strict_types=1);

namespace App\Api\Resource;

class CreateUser
{
    /**
     * @var string|null
     */
    public ?string $email = null;

    /**
     * @var string|null
     */
    public ?string $password = null;

    /**
     * @var string|null
     */
    public ?string $firstname = null;

    /**
     * @var string|null
     */
    public ?string $lastname = null;
}
