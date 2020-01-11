<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Execution\Event;

use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Event\ExecutionCreated;
use PHPUnit\Framework\TestCase;

class ExecutionCreatedTest extends TestCase
{

    public function testCreate(): void
    {
        $execution = $this->prophesize(Execution::class)->reveal();

        $executionCreated = new ExecutionCreated($execution);
        $this->assertEquals($execution, $executionCreated->getExecution());
    }
}
