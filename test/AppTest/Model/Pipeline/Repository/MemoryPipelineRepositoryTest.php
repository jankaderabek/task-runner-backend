<?php

namespace AppTest\Model\Pipeline\Repository;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Action\Repository\ActionRepository;
use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Repository\MemoryPipelineRepository;
use PHPUnit\Framework\TestCase;

class MemoryPipelineRepositoryTest extends TestCase
{

    public function testFetch(): void
    {
        $actionRepository = $this->prophesize(ActionRepository::class);
        $actionRepository
            ->fetch(\Prophecy\Argument::type('int'))
            ->willReturn($this->prophesize(Action::class)->reveal())
        ;

        $memoryPipelineRepository = new MemoryPipelineRepository($actionRepository->reveal());

        $this->assertNull($memoryPipelineRepository->fetch(666));

        $pipeline = $memoryPipelineRepository->fetch(1);
        $this->assertInstanceOf(Pipeline::class, $pipeline);
        $this->assertCount(1, $pipeline->getActions());
    }

    public function testFindAll(): void
    {
        $actionRepository = $this->prophesize(ActionRepository::class);
        $actionRepository
            ->fetch(\Prophecy\Argument::type('int'))
            ->willReturn($this->prophesize(Action::class)->reveal())
        ;

        $memoryPipelineRepository = new MemoryPipelineRepository($actionRepository->reveal());

        $pipelines = $memoryPipelineRepository->findAll();
        $this->assertCount(2, $pipelines);
        $this->assertInstanceOf(Pipeline::class, \current($pipelines));
    }
}
