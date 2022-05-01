<?php

declare(strict_types=1);

namespace App\Security\Handler;

use App\Security\Factory\UserFactory;
use App\Security\Model\Registration;
use App\Security\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserFactory $userFactory,
        private readonly UserRepository $userRepository,
    ) {
    }

    public function __invoke(Registration $registration): void
    {
        $hashedPassword = $this->hasher->hashPassword($this->getFakeUser(), $registration->password);

        $user = $this->userFactory->createByEmailAndPassword($registration->email, $hashedPassword);

        $this->userRepository->save($user);
    }

    private function getFakeUser(): PasswordAuthenticatedUserInterface
    {
        return new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        };
    }
}
