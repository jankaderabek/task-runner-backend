<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Execution\Repository;

use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Repository\RedisExecutionRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use App\Redis\RedisClient;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class RedisExecutionRepositoryTest extends TestCase
{

    /**
     * @dataProvider fetchProvider
     */
    public function testFetch(string $uuid, ?string $redisStorageData, bool $exists, ?Pipeline $pipeline = null): void
    {
        $redisClient = $this->prophesize(RedisClient::class);
        $redisClient
            ->get('execution-' . $uuid)
            ->willReturn($redisStorageData)
        ;

        $pipelineRepository = $this->prophesize(PipelineRepository::class);
        $pipelineRepository
            ->fetch(123)
            ->willReturn($pipeline)
        ;

        $redisExecutionRepository = new RedisExecutionRepository(
            $redisClient->reveal(),
            $pipelineRepository->reveal(),
        );

        $execution = $redisExecutionRepository->fetch(Uuid::fromString('25769c6c-d34d-4bfe-ba98-e0ee856f3e7a'));

        if ($exists) {
            $this->assertInstanceOf(Execution::class, $execution);
        } else {
            $this->assertNull($execution);
        }
    }

    /**
     * @return array<array<string, mixed>>
     */
    public function fetchProvider(): array
    {
        return array(
            [
                'uuid' => '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a',
                'storedInRedis' => null,
                'exists' => false,
            ],
            [
                'uuid' => '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a',
                'storedInRedis' => 'invalid json',
                'exists' => false,
            ],
            [
                'uuid' => '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a',
                'storedInRedis' => '{"uuid":"25769c6c-d34d-4bfe-ba98-e0ee856f3e7a","pipeline":123}',
                'exists' => false,
            ],
            [
                'uuid' => '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a',
                'storedInRedis' => '{"uuid":"25769c6c-d34d-4bfe-ba98-e0ee856f3e7a","pipeline":123}',
                'exists' => true,
                'pipeline' => $this->prophesize(Pipeline::class)->reveal(),
            ],
        );
    }

    public function testSaveExecution(): void
    {
        $uuid = '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a';
        $redisClient = $this->prophesize(RedisClient::class);
        $redisClient
            ->set(Argument::type('string'), Argument::type('string'))
            ->willReturn(true)
        ;

        $pipelineRepository = $this->prophesize(PipelineRepository::class);

        $redisExecutionRepository = new RedisExecutionRepository(
            $redisClient->reveal(),
            $pipelineRepository->reveal(),
        );

        $pipeline = $this->prophesize(Pipeline::class);
        $pipeline->getId()->willReturn(321);

        $execution = $this->prophesize(Execution::class);
        $execution
            ->getUuid()
            ->willReturn(Uuid::fromString($uuid))
        ;
        $execution
            ->getPipeline()
            ->willReturn($pipeline->reveal())
        ;

        $this->assertNull($redisExecutionRepository->save($execution->reveal()));
    }
}
