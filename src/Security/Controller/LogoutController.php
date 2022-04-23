<?php

declare(strict_types=1);

namespace App\Security\Controller;

use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/logout', name: 'app_logout')]
final class LogoutController extends AbstractController
{
    public function __invoke(): void
    {
        throw new LogicException('This code should never be reached');
    }
}
