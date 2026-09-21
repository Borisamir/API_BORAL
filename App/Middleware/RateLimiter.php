<?php

namespace App\Middleware;

use Exception;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\RateLimiter\Storage\CacheStorage;

class RateLimiter{

    private static function factory(string $id, int $limit, string $interval): RateLimiterFactory{

        $storage = new CacheStorage(new FilesystemAdapter('rate_limiter'));

        return new RateLimiterFactory([
            'id' => $id,
            'policy' => 'sliding_window',
            'limit' => $limit,
            'interval' => $interval
        ], $storage);
    }

    public static function check(string $id, string $key, int $limit, string $interval): void{

        $result = self::factory($id, $limit, $interval)->create($key)->consume();

        if(!$result->isAccepted()){

            $retry_after = max($result->getRetryAfter()->getTimestamp() - time(), 1);

            http_response_code(429);
            header('Retry-After: ' . $retry_after);

            throw new Exception("Demasiados intentos. Intenta nuevamente en {$retry_after} segundos.");
        }
    }

}

?>
