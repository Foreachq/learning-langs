<?php

declare(strict_types=1);

namespace App\Profile\Factory;

use App\Profile\Entity\Profile;

final class ProfileFactory
{
    public function createByNameAndGender(string $firstName, string $lastName, string $gender): Profile
    {
        return new Profile($firstName, $lastName, $gender);
    }
}
