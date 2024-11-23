<?php

declare(strict_types=1);

namespace App\Api\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation;
use App\Controller\LoginController;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[Post(
    uriTemplate: '/login',
    controller: LoginController::class,
    openapi: new Operation(
        summary: 'Authenticates a user by its email and password and returns a token.',
    ),
    read: false,
    deserialize: true,
    name: 'app_login'
)]
class Login
{
    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    #[Assert\NotBlank]
    public ?string $password = null;
}
