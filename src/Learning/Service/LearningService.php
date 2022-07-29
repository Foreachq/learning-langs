<?php

declare(strict_types=1);

namespace App\Learning\Service;

use App\Learning\Repository\WordRepository;
use App\Learning\Service\LearningStrategy\LearningStrategyContext;
use App\Profile\Entity\Profile;
use JetBrains\PhpStorm\ArrayShape;

use function rand;
use function shuffle;

class LearningService
{
    public function __construct(
        private readonly WordRepository $wordRepository,
        private readonly LearningStrategyContext $learningContext,
    ) {
    }

    #[ArrayShape([
        'word'            => "\App\Learning\Entity\Word",
        'answers'         => "array",
        'isWordInEnglish' => "bool",
    ])]
    public function prepareQuestionAndAnswers(Profile $profile): array
    {
        $word = $this->learningContext->getWord($profile);

        $answers = $this->wordRepository->getRandomWords(4, $word);
        $answers[] = $word;
        shuffle($answers);

        $isWordInEnglish = (bool) rand(0, 1);

        return ['word' => $word, 'answers' => $answers, 'isWordInEnglish' => $isWordInEnglish];
    }
}
