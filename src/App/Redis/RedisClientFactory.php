<?php

declare(strict_types=1);

namespace App\Redis;

use Psr\Container\ContainerInterface;

class RedisClientFactory
{

    private const DEFAULT_HOST = '127.0.0.1';


    public function __invoke(ContainerInterface $container): RedisClient
    {
        $config = $container->get('config');
        $appConfig = $config['app-config'] ?? [];
        $redisConfig = $appConfig['redis'] ?? [];

        $host = $redisConfig['host'] ?? self::DEFAULT_HOST;

        return new RedisClient(
            $host,
            new RedisFactory(),
        );
    }
}
