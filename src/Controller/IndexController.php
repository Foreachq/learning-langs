<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/', name: 'app_index')]
final class IndexController extends AbstractController
{
    public function __invoke(#[CurrentUser] $user): Response
    {
        return $this->render('index/index.html.twig', ['user' => $user]);
    }
}
