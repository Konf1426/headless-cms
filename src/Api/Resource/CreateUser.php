<?php

declare(strict_types=1);

namespace App\Api\Resource;

use App\Validator\Constraint\UnregistredEmail;

class CreateUser
{
    /**
     * @var string|null
     */
    #[UnregistredEmail]
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
