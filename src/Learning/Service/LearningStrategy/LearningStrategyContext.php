<?php

declare(strict_types=1);

namespace App\Learning\Service\LearningStrategy;

use App\Learning\Entity\Word;
use App\Learning\Service\LearningStrategy\Strategy\WeightedRandomStrategy;
use App\Profile\Entity\Profile;
use RuntimeException;

use function get_class;

class LearningStrategyContext
{
    /** @var array<LearningStrategyInterface> */
    private array $strategies = [];

    public function addStrategy(LearningStrategyInterface $strategy): void
    {
        $this->strategies[] = $strategy;
    }

    /**
     * @param class-string $userStrategy
     */
    public function getWord(Profile $profile, string $userStrategy = WeightedRandomStrategy::class): Word
    {
        foreach ($this->strategies as $strategy) {
            if (get_class($strategy) === $userStrategy) {
                return $strategy->process($profile);
            }
        }

        throw new RuntimeException('No strategy found.');
    }
}
