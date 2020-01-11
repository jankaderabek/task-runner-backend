<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Execution\Entity;

use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Entity\ExecutionFactory;
use PHPUnit\Framework\TestCase;

class ExecutionFactoryTest extends TestCase
{

    public function testCreateSimpleExecutionInstance(): void
    {
        $executionFactory = new ExecutionFactory();
        $pipeline = $this->prophesize(Pipeline::class)->reveal();

        $createdExecution = $executionFactory->create($pipeline);

        $this->assertInstanceOf(Execution::class, $createdExecution);
        $this->assertEquals($pipeline, $createdExecution->getPipeline());
    }
}
