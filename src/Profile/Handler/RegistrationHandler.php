<?php

declare(strict_types=1);

namespace App\Profile\Handler;

use App\Profile\Factory\ProfileFactory;
use App\Profile\Model\Registration;
use App\Profile\Service\RegistrationService;
use App\Security\Factory\UserFactory;
use App\Security\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationHandler
{
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly UserFactory $userFactory,
        private readonly ProfileFactory $profileFactory,
        private readonly UserRepository $userRepository,
        private readonly RegistrationService $registrationService,
    ) {
    }

    public function handle(Registration $registration): void
    {
        $user = $this->userFactory->createByEmailAndPassword($registration->email, $registration->password);
        $hashedPassword = $this->hasher->hashPassword($user, $registration->password);

        $user->setPassword($hashedPassword);
        $profile = $this->profileFactory->createByNameAndGender(
            $registration->firstName,
            $registration->lastName,
            $registration->gender,
        );

        $user->setProfile($profile);
        $this->userRepository->save($user);

        $this->registrationService->sendRegisterEmail(
            $registration->email,
            $registration->firstName,
            $registration->lastName,
        );
    }
}
