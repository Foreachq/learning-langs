<?php

declare(strict_types=1);

namespace App\Security\Controller;

use App\Infrastructure\Util\View;
use App\Security\Handler\RegistrationHandler;
use App\Security\Model\Registration;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/registration')]
final class RegistrationController
{
    public function __construct(
        private readonly DenormalizerInterface $serializer,
        private readonly RegistrationHandler $handler,
        private readonly View $view,
    ) {
    }

    #[Route('/create', name: 'security_registration_create', methods: Request::METHOD_GET)]
    public function create(): Response
    {
        return $this->view->render('@security/registration.html.twig');
    }

    #[Route('/', name: 'security_registration_store', methods: Request::METHOD_POST)]
    public function store(Request $request): RedirectResponse
    {
        $registration = $this->serializer->denormalize($request->request->all(), Registration::class);

        ($this->handler)($registration);

        return $this->view->redirectToRoute('security_login');
    }
}
