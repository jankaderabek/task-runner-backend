<?php

declare(strict_types=1);

namespace AppTest\Redis;

use App\Redis\RedisClient;
use App\Redis\RedisFactory;
use PHPUnit\Framework\TestCase;
use Redis;

class RedisClientTest extends TestCase
{

    public function testSet(): void
    {
        $redis = $this->prophesize(Redis::class);
        $redis
            ->connect('localhost')
            ->willReturn(true)
        ;
        $redis
            ->set('key1', 'value123')
            ->willReturn(true)
        ;

        $redisFactory = $this->prophesize(RedisFactory::class);
        $redisFactory->create()->willReturn($redis->reveal());

        $redisClient = new RedisClient('localhost', $redisFactory->reveal());
        $this->assertTrue($redisClient->set('key1', 'value123'));
    }

    /**
     * @dataProvider getProvider
     */
    public function testGet(bool $hasData, ?string $stored, ?string $result): void
    {
        $redis = $this->prophesize(Redis::class);
        $redis
            ->connect('localhost')
            ->willReturn(true)
        ;
        $redis
            ->get('key1')
            ->willReturn($hasData ? $stored : false)
        ;

        $redisFactory = $this->prophesize(RedisFactory::class);
        $redisFactory->create()->willReturn($redis->reveal());

        $redisClient = new RedisClient('localhost', $redisFactory->reveal());
        $this->assertEquals($result, $redisClient->get('key1'));
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function getProvider(): array
    {
        return [
            [
                'hasData' => false,
                'stored' => null,
                'result' => null,
            ],
            [
                'hasData' => true,
                'stored' => 'testdata',
                'result' => 'testdata',
            ],
        ];
    }
}
