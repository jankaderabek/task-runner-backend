<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Execution\Entity;

use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Execution\Entity\SimpleExecution;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class SimpleExecutionTest extends TestCase
{

    private const EXECUTION_UUID = '25769c6c-d34d-4bfe-ba98-e0ee856f3e7a';

    public function testCreateExecutionEntity(): void
    {
        $pipeline = $this->prophesize(Pipeline::class)->reveal();
        $uuid = Uuid::fromString(self::EXECUTION_UUID);

        $createdExecution = new SimpleExecution(
            $uuid,
            $pipeline,
        );

        $this->assertTrue($uuid->equals($createdExecution->getUuid()));
        $this->assertEquals($pipeline, $createdExecution->getPipeline());
    }
}
