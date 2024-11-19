<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): User {
        $user = new User();
        if (!filter_var($data->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email');
        }

        $user->email = $data->email;
        $user->password = $this->hasher->hashPassword($user, $data->password);
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;
        $user->roles = ['ROLE_USER'];

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}