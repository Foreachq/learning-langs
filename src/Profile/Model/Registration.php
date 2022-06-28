<?php

declare(strict_types=1);

namespace App\Profile\Model;

final class Registration
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $gender,
    ) {
    }
}
