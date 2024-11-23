<?php

namespace App\Tests;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserHasRoleUser(): void
    {
        $user = new User();
        $this->assertContains('ROLE_USER', $user->getRoles());
    }
}
