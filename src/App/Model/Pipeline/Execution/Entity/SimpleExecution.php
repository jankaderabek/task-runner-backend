<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Entity;

use App\Model\Pipeline\Entity\Pipeline;
use Ramsey\Uuid\UuidInterface;

final class SimpleExecution implements Execution
{

    private UuidInterface $uuid;

    private Pipeline $pipeline;

    public function __construct(
        UuidInterface $uuid,
        Pipeline $pipeline
    ) {
        $this->uuid = $uuid;
        $this->pipeline = $pipeline;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getPipeline(): Pipeline
    {
        return $this->pipeline;
    }
}
