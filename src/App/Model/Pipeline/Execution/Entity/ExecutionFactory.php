<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Entity;

use App\Model\Pipeline\Entity\Pipeline;
use Ramsey\Uuid\Uuid;

class ExecutionFactory
{
    public function create(Pipeline $pipeline): Execution
    {
        return new SimpleExecution(Uuid::uuid4(), $pipeline);
    }
}
