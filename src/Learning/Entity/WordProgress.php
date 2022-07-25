<?php

declare(strict_types=1);

namespace App\Learning\Entity;

use App\Learning\Repository\WordProgressRepository;
use App\Profile\Entity\Profile;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WordProgressRepository::class)]
#[ORM\Table(name: 'words_progress')]
#[ORM\UniqueConstraint(columns: ['profile_id', 'word_id'])]

class WordProgress
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Profile::class, cascade: ['persist'], inversedBy: 'wordsProgress')]
    #[ORM\JoinColumn(name: 'profile_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private Profile $profile;

    #[ORM\ManyToOne(targetEntity: Word::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'word_id', referencedColumnName: 'id', nullable: false)]
    #[Assert\NotBlank]
    private Word $word;

    #[ORM\Column(type: Types::BOOLEAN)]
    private bool $active = false;

    #[ORM\Column(type: Types::INTEGER)]
    private int $attemptsCount = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private int $correctAnswersCount = 0;

    public function __construct(Profile $profile, Word $word)
    {
        $this->profile = $profile;
        $this->word = $word;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProfile(): Profile
    {
        return $this->profile;
    }

    public function setProfile(Profile $profile): WordProgress
    {
        $this->profile = $profile;

        return $this;
    }

    public function getWord(): Word
    {
        return $this->word;
    }

    public function setWord(Word $word): WordProgress
    {
        $this->word = $word;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): WordProgress
    {
        $this->active = $active;

        return $this;
    }

    public function getAttemptsCount(): int
    {
        return $this->attemptsCount;
    }

    public function setAttemptsCount(int $attemptsCount): WordProgress
    {
        $this->attemptsCount = $attemptsCount;

        return $this;
    }

    public function getCorrectAnswersCount(): int
    {
        return $this->correctAnswersCount;
    }

    public function setCorrectAnswersCount(int $correctAnswersCount): WordProgress
    {
        $this->correctAnswersCount = $correctAnswersCount;

        return $this;
    }
}
