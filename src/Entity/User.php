<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Enum\RoleEnum;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: TableEnum::USER)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection,
        new Get(security: RoleEnum::IS_ADMIN_OR_USER_OBJECT),
        new Post(input: CreateUser::class, processor: CreateUserProcessor::class),
        new Patch(
            denormalizationContext: ['groups' => ['user:update']],
            security: RoleEnum::IS_ADMIN_OR_USER_OBJECT
        ),
        new Delete(security: RoleEnum::IS_GRANTED_ADMIN),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(min: 1, max: 180)]
    #[Groups(['comment:read', 'user:update', 'content:read'])]
    public ?string $firstname = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(min: 1, max: 180)]
    #[Groups(['comment:read', 'user:update', 'content:read'])]
    public ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 180)]
    #[Groups(['comment:read', 'user:update', 'content:read'])] // ✅ Ajout ici
    public ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    #[Groups(['user:update'])]
    public array $roles = [];

    #[ORM\Column]
    #[Ignore]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    public ?string $password = null;

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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void {}
}
