<?php

namespace App\Lib\Security\RateLimiter;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class RateLimitListener
{
    private RateLimiter $rateLimiter;

    public function __construct(RateLimiter $rateLimiter)
    {
        $this->rateLimiter = $rateLimiter;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        /*$ip = $event->getRequest()->getClientIp();
        if (!$this->rateLimiter->limit($ip)) {
            $response = new Response('Rate limit exceeded', 429);
            $event->setResponse($response);
        }*/
    }
}