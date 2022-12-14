<?php

declare(strict_types=1);

namespace App\Profile\Entity;

use App\Learning\Entity\WordProgress;
use App\Profile\Repository\ProfileRepository;
use App\Security\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
#[ORM\Table(name: 'profiles')]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 64)]
    #[Assert\NotBlank]
    private string $firstName;

    #[ORM\Column(type: Types::STRING, length: 64)]
    #[Assert\NotBlank]
    private string $lastName;

    #[ORM\Column(type: Types::STRING, columnDefinition: 'ENUM(\'male\', \'female\')')]
    #[Assert\Choice(choices: self::GENDERS, message: 'Choose a valid gender.')]
    #[Assert\NotBlank]
    private string $gender;

    #[ORM\OneToOne(inversedBy: 'profile', targetEntity: User::class, cascade: ['persist'])]
    private ?User $user = null;

    /**
     * @var Collection<int, WordProgress>
     */
    #[ORM\OneToMany(mappedBy: 'profile', targetEntity: WordProgress::class, cascade: ['persist'])]
    private Collection $wordsProgress;

    #[ORM\Column(type: Types::BOOLEAN, options: ['default' => false])]
    #[Assert\NotBlank]
    private bool $isVerified = false;

    public const GENDERS = ['male', 'female'];

    public function __construct(string $firstName, string $lastName, string $gender)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->gender = $gender;

        $this->wordsProgress = new ArrayCollection();
    }

    /**
     * @return Collection<int, WordProgress>
     */
    public function getWordsProgress(): Collection
    {
        return $this->wordsProgress;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function setIsVerified(bool $isConfirmed): self
    {
        $this->isVerified = $isConfirmed;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }
}
