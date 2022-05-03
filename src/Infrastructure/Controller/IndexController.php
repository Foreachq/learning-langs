<?php

declare(strict_types=1);

namespace App\Infrastructure\Controller;

use App\Infrastructure\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/', name: 'infrastructure_index')]
final class IndexController
{
    public function __construct(
        private readonly View $view,
    ) {
    }

    public function __invoke(#[CurrentUser] UserInterface $user): Response
    {
        return $this->view->render('@infrastructure/index.html.twig', ['user' => $user]);
    }
}
