<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Repository;

use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Entity\SimpleExecution;
use App\Model\Pipeline\Repository\PipelineRepository;
use App\Redis\RedisClient;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class RedisExecutionRepository implements ExecutionRepository
{

    private RedisClient $redisClient;

    private PipelineRepository $pipelineRepository;

    public function __construct(
        RedisClient $redisClient,
        PipelineRepository $pipelineRepository
    ) {
        $this->redisClient = $redisClient;
        $this->pipelineRepository = $pipelineRepository;
    }

    public function fetch(UuidInterface $uuid): ?Execution
    {
        $serializedExecution = $this->redisClient->get($this->getExecutionKey($uuid));

        if (!$serializedExecution) {
            return null;
        }

        $executionData = \json_decode($serializedExecution, true);

        if (!$executionData) {
            return null;
        }

        $pipeline = $this->pipelineRepository->fetch($executionData['pipeline']);

        if (!$pipeline) {
            return null;
        }

        return new SimpleExecution(Uuid::fromString($executionData['uuid']), $pipeline);
    }

    /**
     * @throws Exception\DataSerializationException
     */
    public function save(Execution $execution): void
    {
        $jsonExecution = \json_encode([
            'uuid' => $execution->getUuid(),
            'pipeline' => $execution->getPipeline()->getId(),
        ]);

        if (!$jsonExecution) {
            throw new Exception\DataSerializationException();
        }

        $this->redisClient->set(
            $this->getExecutionKey($execution->getUuid()),
            $jsonExecution,
        );
    }

    private function getExecutionKey(UuidInterface $uuid): string
    {
        return \sprintf('execution-%s', $uuid->toString());
    }
}
