<?php

declare(strict_types=1);

namespace App\Learning\Entity;

use App\Learning\Repository\WordRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: WordRepository::class)]
#[ORM\Table(name: 'words')]
class Word
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 64)]
    #[Assert\NotBlank]
    private string $english;

    #[ORM\Column(type: Types::STRING, length: 64)]
    #[Assert\NotBlank]
    private string $russian;

    public function __construct(string $english, string $russian)
    {
        $this->english = $english;
        $this->russian = $russian;
    }

    public function getEnglish(): string
    {
        return $this->english;
    }

    public function setEnglish(string $english): self
    {
        $this->english = $english;

        return $this;
    }

    public function getRussian(): string
    {
        return $this->russian;
    }

    public function setRussian(string $russian): self
    {
        $this->russian = $russian;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
