<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use App\Validator\Constraint\UnregistredEmail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UnregistredEmailValidator extends ConstraintValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return void
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UnregistredEmail) {
            throw new UnexpectedTypeException($constraint, UnregistredEmail::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if ($this->entityManager->getRepository(User::class)->findOneBy(['email' => $value])) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}
