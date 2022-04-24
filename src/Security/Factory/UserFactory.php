<?php

declare(strict_types=1);

namespace App\Security\Factory;

use App\Security\Entity\User;

final class UserFactory
{
    public function createByEmailAndPassword(string $email, string $password): User
    {
        return new User($email, $password);
    }
}
