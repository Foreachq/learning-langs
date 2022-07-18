<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Infrastructure\Factory\EmailFactory;
use App\Security\Entity\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    private string $emailFrom;
    private string $host;

    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly EmailFactory $emailFactory,
        string $emailFrom,
        string $host,
    ) {
        $this->emailFrom = $emailFrom;
        $this->host = $host;
    }

    public function sendConfirmationEmail(User $user, string $confirmationLink): void
    {
        $profile = $user->getProfile();
        $email = $this->emailFactory->createTemplated(
            $user->getEmail(),
            $this->emailFrom,
            'Learning Languages registration',
            '@profile/registration_email.html.twig',
            [
                'user_first_name'  => $profile->getFirstName(),
                'user_last_name'   => $profile->getLastName(),
                'host'             => $this->host,
                'confirmation_url' => $confirmationLink,
            ],
        );

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
            // TODO: Handle exception
        }
    }
}
