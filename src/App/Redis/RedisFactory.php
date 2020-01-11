<?php

declare(strict_types=1);

namespace App\Redis;

use Redis;

class RedisFactory
{
    public function create(): Redis
    {
        return new Redis();
    }
}
