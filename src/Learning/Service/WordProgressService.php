<?php

declare(strict_types=1);

namespace App\Learning\Service;

use App\Learning\Entity\Word;
use App\Learning\Entity\WordProgress;
use App\Learning\Repository\WordProgressRepository;
use App\Profile\Entity\Profile;
use Doctrine\Common\Collections\Collection;

class WordProgressService
{
    public function __construct(
        private readonly WordProgressRepository $progressRepository,
    ) {
    }

    /**
     * @return Collection<int, Word>
     */
    public function getLearningWords(Profile $profile): Collection
    {
        return $profile
            ->getWordsProgress()
            ->map(fn(WordProgress $progress) => $progress->getWord());
    }

    /**
     * @return Collection<int, Word>
     */
    public function getActiveWords(Profile $profile): Collection
    {
        return $profile
            ->getWordsProgress()
            ->filter(fn(WordProgress $progress) => $progress->isActive())
            ->map(fn(WordProgress $progress) => $progress->getWord());
    }

    public function addWordToLearning(Word $word, Profile $profile): void
    {
        $progress = new WordProgress($profile, $word);
        $this->progressRepository->save($progress);
    }

    public function isWordBeingLearnt(Word $word, Profile $profile): bool
    {
        return $this->getLearningWords($profile)->contains($word);
    }
}
