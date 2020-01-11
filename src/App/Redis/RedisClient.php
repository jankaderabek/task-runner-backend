<?php

declare(strict_types=1);

namespace App\Redis;

use Redis;

class RedisClient
{
    private Redis $redis;

    public function __construct(string $host, RedisFactory $redisFactory)
    {
        $this->redis = $redisFactory->create();
        $this->redis->connect($host);
    }

    public function set(string $key, string $value): bool
    {
        return $this->redis->set($key, $value);
    }

    public function get(string $key): ?string
    {
        $record = $this->redis->get($key);

        return $record ? $record : null;
    }
}
