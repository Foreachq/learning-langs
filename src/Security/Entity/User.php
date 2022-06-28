<?php

declare(strict_types=1);

namespace App\Security\Entity;

use App\Profile\Entity\Profile;
use App\Security\Repository\UserRepository;
use App\Security\Traits\UserTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[UniqueEntity(fields: ['email'], message: 'User with this email already exists')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use UserTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 180, unique: true)]
    #[Assert\Email]
    #[Assert\NotBlank]
    private string $email = '';

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $password = null;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Profile::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'profile_id', referencedColumnName: 'id')]
    private ?Profile $profile = null;

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfile(): ?Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): self
    {
        $this->profile = $profile;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
