<?php

declare(strict_types=1);

namespace App\Traits;

use RuntimeException;

use function sprintf;

trait UserTrait
{
    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getUsername(): string
    {
        throw new RuntimeException(sprintf('Method "getUsername" not supported in "%s"', self::class));
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
