<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource]
#[GetCollection(security: "is_granted('ROLE_USER')")]
#[Post(input: CreateUser::class, processor: CreateUserProcessor::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180)]
    public ?string $firstname = null;

    #[ORM\Column(length: 180)]
    public ?string $lastname = null;

    #[ORM\Column(length: 180)]
    public ?string $email = null;

    #[ORM\Column]
    public array $roles = [];

    #[ORM\Column]
    public ?string $password = null;

    public function __construct()
    {
        $this->defineId();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
