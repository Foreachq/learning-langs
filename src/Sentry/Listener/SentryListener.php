<?php

declare(strict_types=1);

namespace App\Sentry\Listener;

use ReflectionClass;
use Sentry\State\HubInterface;
use Sentry\State\Scope;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

final class SentryListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly HubInterface $hub,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST    => ['onKernelRequest', 1],
            KernelEvents::CONTROLLER => ['onKernelController', 10000],
            KernelEvents::TERMINATE  => ['onKernelTerminate', 1],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $userData = ['ip_address' => $event->getRequest()->getClientIp()];
        $user = $this->security->getUser();

        if ($user) {
            $userData['type'] = (new ReflectionClass($user))->getShortName();
            $userData['username'] = $user->getUserIdentifier();
            $userData['roles'] = $user->getRoles();
        }

        $this->hub->configureScope(static function (Scope $scope) use ($userData): void {
            $scope->setUser($userData);
        });
    }

    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (!$event->getRequest()->attributes->has('_route')) {
            return;
        }

        $matchedRoute = (string) $event->getRequest()->attributes->get('_route');

        $this->hub->configureScope(static function (Scope $scope) use ($matchedRoute): void {
            $scope->setTag('route', $matchedRoute);
        });
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $statusCode = $event->getResponse()->getStatusCode();

        $this->hub->configureScope(static function (Scope $scope) use ($statusCode): void {
            $scope->setTag('status_code', (string) $statusCode);
        });
    }
}
