<?php

declare(strict_types=1);

namespace App\Security\Controller;

use App\Infrastructure\Service\EmailService;
use App\Infrastructure\Service\VerificationLinkGenerator;
use App\Infrastructure\Util\View;
use App\Security\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

#[Route('/verify')]
final class VerificationController extends AbstractController
{
    public function __construct(
        private readonly View $view,
        private readonly EmailService $emailService,
        private readonly UserRepository $userRepository,
        private readonly VerifyEmailHelperInterface $verifyEmailHelper,
        private readonly VerificationLinkGenerator $verificationService,
    ) {
    }


    #[Route('/send-link/{userId}', name: 'security_verification_send_link', requirements: ['userId' => '\d+'])]
    public function sendVerificationLink(UserRepository $userRepository, int $userId): Response
    {
        $user = $userRepository->requireOne($userId);

        $confirmationLink = $this->verificationService->generateConfirmationLink($user);
        $this->emailService->sendConfirmationEmail($user, $confirmationLink);

        $this->addFlash('success', 'Verification email was sent to your email');

        return $this->view->redirectToRoute('security_login');
    }

    #[Route('/verify-link', name: 'security_verification_verify_link')]
    public function verifyUserEmail(Request $request): Response
    {
        $id = $request->query->get('id');

        if (null === $id) {
            throw $this->createNotFoundException();
        }

        $user = $this->userRepository->requireOne($id);

        try {
            $this->verifyEmailHelper->validateEmailConfirmation(
                $request->getUri(),
                (string) $user->getId(),
                $user->getEmail(),
            );
        } catch (VerifyEmailExceptionInterface $e) {
            $this->addFlash('error', $e->getReason());

            return $this->redirectToRoute('security_login');
        }

        $user->getProfile()->setIsVerified(true);
        $this->userRepository->save($user);

        $this->addFlash('success', 'Email confirmed successfully');

        return $this->redirectToRoute('infrastructure_index');
    }
}
