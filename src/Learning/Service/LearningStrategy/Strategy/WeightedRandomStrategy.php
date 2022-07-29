<?php

declare(strict_types=1);

namespace App\Learning\Service\LearningStrategy\Strategy;

use App\Learning\Entity\Word;
use App\Learning\Entity\WordProgress;
use App\Learning\Service\LearningStrategy\LearningStrategyInterface;
use App\Profile\Entity\Profile;
use RuntimeException;

use function array_map;
use function array_values;
use function count;
use function max;
use function rand;

class WeightedRandomStrategy implements LearningStrategyInterface
{
    public function process(Profile $profile): Word
    {
        $activeWordsProgress = $profile->getWordsProgress()
            ->filter(fn(WordProgress $progress) => $progress->isActive());
        $weights = $activeWordsProgress
            ->map(fn(WordProgress $progress) => $progress->getCorrectAnswersCount());
        $words = $activeWordsProgress->map(fn(WordProgress $progress) => $progress->getWord());

        // reset numeration
        $words = array_values($words->toArray());
        $weights = array_values($weights->toArray());

        return $this->getRandomWord($words, $weights);
    }

    /**
     * @param array<Word> $words
     * @param array<int> $weights
     */
    private function getRandomWord(array $words, array $weights): Word
    {
        if (!count($words)) {
            throw new RuntimeException('Items must not be empty');
        }

        $weights = $this->inverseWeights($weights);

        $cumulativeWeights = [];
        $cumulativeWeights[0] = $weights[0];

        for ($i = 1; $i < count($words); $i++) {
            $cumulativeWeights[$i] = $weights[$i] + $cumulativeWeights[$i - 1];
        }

        $random = rand(1, $cumulativeWeights[count($cumulativeWeights) - 1]);

        foreach ($cumulativeWeights as $index => $weight) {
            if ($weight >= $random) {
                return $words[$index];
            }
        }

        return $words[count($cumulativeWeights) - 1];
    }

    /**
     * @param array<int> $weights
     * @return array<int>
     */
    private function inverseWeights(array $weights): array
    {
        if (!count($weights)) {
            return [];
        }

        $inverseValue = max($weights) + 1;

        return array_map(fn (int $weight) => $inverseValue - $weight, $weights);
    }
}
