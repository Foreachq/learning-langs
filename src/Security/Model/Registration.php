<?php

declare(strict_types=1);

namespace App\Security\Model;

final class Registration
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {
    }
}
