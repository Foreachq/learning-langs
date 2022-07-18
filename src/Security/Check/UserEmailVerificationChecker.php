<?php

declare(strict_types=1);

namespace App\Security\Check;

use App\Security\Repository\UserRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function sprintf;

class UserEmailVerificationChecker implements UserCheckerInterface
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UrlGeneratorInterface $router,
    ) {
    }

    public function checkPreAuth(UserInterface $user): void
    {
        $user = $this->userRepository->requireOneBy(['email' => $user->getUserIdentifier()]);

        if (!$user->getProfile()->isVerified()) {
            throw new CustomUserMessageAccountStatusException(
                sprintf(
                    'Your user account is not verified. <a href="%s">Send verification email again.</a>',
                    $this->router->generate('security_verification_send_link', ['userId' => $user->getId()]),
                ),
            );
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
    }
}
