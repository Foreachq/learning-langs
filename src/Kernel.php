<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureContainer(ContainerConfigurator $container): void
    {
        $container->import("{$this->getConfigDir()}/{packages}/*.yaml");
        $container->import('./**/services.yaml');
    }

    private function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('./**/routes.yaml');
    }
}
