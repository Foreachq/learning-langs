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
        $fakeUser = new class implements PasswordAuthenticatedUserInterface {
            public function getPassword(): ?string
            {
                return null;
            }
        };

        $hashedPassword = $this->hasher->hashPassword($fakeUser, $registration->password);

        $user = $this->userFactory->createByEmailAndPassword($registration->email, $hashedPassword);

        $this->userRepository->save($user);
    }
}
