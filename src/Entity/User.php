<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use App\Traits\UserTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserTrait;

    #[ORM\Id]
    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    public readonly string $email;

    #[ORM\Column(type: Types::STRING)]
    public ?string $password = null;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
