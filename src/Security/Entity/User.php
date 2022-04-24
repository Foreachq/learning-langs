<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Security\Repository\UserRepository;
use App\Security\Traits\UserTrait;
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
    public string $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }
}
