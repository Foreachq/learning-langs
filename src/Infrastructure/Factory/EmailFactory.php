<?php

declare(strict_types=1);

namespace App\Infrastructure\Factory;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;

final class EmailFactory
{
    public function createTemplated(
        string $emailTo,
        string $emailFrom,
        string $subject,
        string $templatePath,
        array $context = [],
    ): TemplatedEmail {
        return (new TemplatedEmail())
            ->to($emailTo)
            ->from($emailFrom)
            ->subject($subject)
            ->htmlTemplate($templatePath)
            ->context($context);
    }
}
