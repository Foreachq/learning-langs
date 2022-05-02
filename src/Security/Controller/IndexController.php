<?php

declare(strict_types=1);

namespace App\Security\Controller;

use App\Core\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/', name: 'app_index')]
final class IndexController
{
    public function __construct(
        private readonly View $view,
    ) {
    }

    public function __invoke(#[CurrentUser] UserInterface $user): Response
    {
        return $this->view->render('index/index.html.twig', ['user' => $user]);
    }
}
