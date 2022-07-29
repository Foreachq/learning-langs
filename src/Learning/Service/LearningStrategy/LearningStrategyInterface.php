<?php

declare(strict_types=1);

namespace App\Learning\Service\LearningStrategy;

use App\Learning\Entity\Word;
use App\Profile\Entity\Profile;

interface LearningStrategyInterface
{
    public function process(Profile $profile): Word;
}
