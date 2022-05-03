<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    private function configureContainer(ContainerConfigurator $container): void
    {
        $configDir = $this->getConfigDir();

        $container->import("{$configDir}/{packages}/*.yaml");
        $container->import("{$configDir}/{packages}/{$this->environment}/*.yaml");

        $container->import("./**/services.yaml");
        $container->import("./**/{services}_{$this->environment}.yaml");
    }
}
