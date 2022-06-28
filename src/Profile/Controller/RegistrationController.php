<?php

declare(strict_types=1);

namespace App\Profile\Controller;

use App\Profile\Form\RegistrationType;
use App\Profile\Handler\RegistrationHandler;
use App\Profile\Model\Registration;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

use function compact;

#[Route('/registration')]
final class RegistrationController extends AbstractController
{
    public function __construct(
        private readonly DenormalizerInterface $serializer,
        private readonly RegistrationHandler $registrationHandler,
    ) {
    }

    #[Route(
        '/',
        name: 'profile_registration_register',
        methods: [Request::METHOD_POST, Request::METHOD_GET],
    )]
    public function register(Request $request): Response
    {
        $form = $this->createForm(RegistrationType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registration = $this->serializer->denormalize(
                $request->request->get($form->getName()),
                Registration::class,
            );

            $this->registrationHandler->handle($registration);

            return $this->redirectToRoute('security_login');
        }

        return $this->renderForm('@profile/registration.html.twig', compact('form'));
    }
}
