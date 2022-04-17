<?php

declare(strict_types=1);

namespace App\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/login_link_check', name: 'app_login_link_check')]
final class LoginLinkCheckController extends AbstractController
{
    public function __invoke(): void
    {
        throw new LogicException('This code should never be reached');
    }
}
