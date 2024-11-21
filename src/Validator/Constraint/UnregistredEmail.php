<?php

declare(strict_types=1);

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class UnregistredEmail extends Constraint
{
    public string $message = 'L\'email "{{ string }}" est déjà lié à un utilisateur.';

    /**
     * @param string|null $message
     * @param string[]|null $groups
     * @param $payload
     */
    public function __construct(?string $message = null, ?array $groups = null, $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->message = $message ?? $this->message;
    }
}
