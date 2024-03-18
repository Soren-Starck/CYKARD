<?php


namespace App\Lib\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class JwtSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        if (!str_starts_with($request->getPathInfo(), '/api')) return;

        $authorizationHeader = $request->headers->get('Authorization');

        if (!$authorizationHeader) throw new AccessDeniedHttpException('Authorization header is missing');

        if (!str_starts_with($authorizationHeader, 'Bearer ')) throw new AccessDeniedHttpException('Invalid Authorization header format');

        $jwt = substr($authorizationHeader, 7);

        $decoded = JsonWebToken::decoder($jwt);

        if (!$decoded) throw new AccessDeniedHttpException('Invalid JWT');
    }
}