<?php

declare(strict_types=1);

namespace App\Profile\Service;

use App\Infrastructure\Factory\EmailFactory;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class RegistrationService
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

    public function sendRegisterEmail(string $emailTo, string $firstName, string $lastName): void
    {
        $email = $this->emailFactory->createTemplated(
            $emailTo,
            $this->emailFrom,
            'Learning Languages registration',
            '@profile/registration_email.html.twig',
            [
                'user_first_name' => $firstName,
                'user_last_name'  => $lastName,
                'host'            => $this->host,
            ],
        );

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface) {
            // TODO: Handle exception
        }
    }
}
