<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Api\Processor\CreateUserProcessor;
use App\Api\Resource\CreateUser;
use App\Doctrine\Enum\RoleEnum;
use App\Doctrine\Enum\TableEnum;
use App\Doctrine\Traits\UuidTrait;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Attribute\Ignore;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: TableEnum::USER)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(security: RoleEnum::IS_GRANTED_ADMIN),
        new Get(security: RoleEnum::IS_ADMIN_OR_AUTHOR_OBJECT),
        new Post(input: CreateUser::class, processor: CreateUserProcessor::class),
        new Put(security: RoleEnum::IS_GRANTED_ADMIN),
        new Delete(security: RoleEnum::IS_GRANTED_ADMIN),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['email' => 'partial'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UuidTrait;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(min: 1, max: 180)]
    public ?string $firstname = null;

    #[ORM\Column(length: 180, nullable: true)]
    #[Assert\Length(min: 1, max: 180)]
    public ?string $lastname = null;

    #[ORM\Column(length: 180)]
    #[Assert\Email]
    #[Assert\Length(min: 5, max: 180)]
    public ?string $email = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    #[Assert\NotBlank]
    public array $roles = [];

    #[ORM\Column]
    #[Ignore]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 255)]
    public ?string $password = null;

    /**
     * @return string
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @return array|string[]
     */
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

    /**
     * @param string $password
     * @return $this
     */
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
    }
}
