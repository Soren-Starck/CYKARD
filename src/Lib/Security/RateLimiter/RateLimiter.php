<?php

namespace App\Lib\Security\RateLimiter;

use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;

class RateLimiter
{
    private FilesystemAdapter $cache;
    private int $limit;
    private int $time;

    public function __construct(int $limit = 100, int $time = 3600)
    {
        $this->cache = new FilesystemAdapter();
        $this->limit = $limit;
        $this->time = $time;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function limit(string $ip): bool
    {
        $ip = $this->sanitizeIp($ip);
        $item = $this->cache->getItem($ip);
        if (!$item->isHit()) {
            $item->set(0);
            $item->expiresAfter($this->time);
        }

        $requests = $item->get();
        if ($requests >= $this->limit) {
            return false;
        }

        $item->set($requests + 1);
        $this->cache->save($item);

        return true;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function getCurrentRequests(string $ip): int
    {
        $ip = $this->sanitizeIp($ip);
        $item = $this->cache->getItem($ip);
        return $item->isHit() ? $item->get() : 0;
    }

    private function sanitizeIp(string $ip): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $ip);
    }
}