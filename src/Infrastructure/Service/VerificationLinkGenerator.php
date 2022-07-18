<?php

declare(strict_types=1);

namespace App\Infrastructure\Service;

use App\Security\Entity\User;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class VerificationLinkGenerator
{
    public function __construct(
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
    ) {
    }

    public function generateConfirmationLink(User $user): string
    {
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            'security_verification_verify_link',
            (string) $user->getId(),
            $user->getEmail(),
            ['id' => $user->getId()],
        );

        return $signatureComponents->getSignedUrl();
    }
}
