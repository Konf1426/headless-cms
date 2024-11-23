<?php

declare(strict_types=1);

namespace App\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Entity\User;
use App\Validator\Constraint\UnregistredEmail;
use App\Validator\UnregistredEmailValidator;
use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<User, Operation>
 */
final readonly class CreateUserProcessor implements ProcessorInterface
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $hasher,
        private UnregistredEmailValidator $validator
    ) {
    }

    /**
     * @param mixed $data
     * @param Operation $operation
     * @param array<string, mixed> $uriVariables
     * @param array<string, mixed> $context
     * @return object
     */
    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = [],
    ): object {
        $user = new User();
        $this->validator->validate($data->email, new UnregistredEmail());

        $user->email = $data->email;
        $user->password = $this->hasher->hashPassword($user, $data->password);
        $user->firstname = $data->firstname;
        $user->lastname = $data->lastname;

        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }
}
