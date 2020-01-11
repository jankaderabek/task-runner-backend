<?php

declare(strict_types=1);

namespace AppTest\Http\Pipeline\Execution\Start\Facade;

use App\Http\Pipeline\Execution\Start\Exception\UndefinedPipelineException;
use App\Http\Pipeline\Execution\Start\Facade\ExecutionStartFacade;
use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Entity\ExecutionFactory;
use App\Model\Pipeline\Execution\Event\ExecutionCreated;
use App\Model\Pipeline\Execution\Repository\ExecutionRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Swoole\Http\Server as SwooleServer;

class ExecutionStartFacadeTest extends TestCase
{

    public function testStart(): void
    {
        $pipelineRepository = $this->prophesize(PipelineRepository::class);
        $pipelineRepository
            ->fetch(Argument::type('int'))
            ->willReturn($this->prophesize(Pipeline::class)->reveal())
        ;

        $execution = $this->prophesize(Execution::class);

        $executionFactory = $this->prophesize(ExecutionFactory::class);
        $executionFactory
            ->create(Argument::type(Pipeline::class))
            ->willReturn($execution->reveal())
        ;

        $executionRepository = $this->prophesize(ExecutionRepository::class);
        $swooleServer = $this->prophesize(SwooleServer::class);

        $executionStartFacade = new ExecutionStartFacade(
            $pipelineRepository->reveal(),
            $executionFactory->reveal(),
            $executionRepository->reveal(),
            $swooleServer->reveal(),
        );

        $startedExecution = $executionStartFacade->start(65);

        $this->assertEquals($execution->reveal(), $startedExecution);
        $executionRepository->save(Argument::type(Execution::class))->shouldHaveBeenCalled();
        $swooleServer->task(Argument::type(ExecutionCreated::class))->shouldHaveBeenCalled();
    }

    public function testStartWithUnknownPipeline(): void
    {
        $pipelineRepository = $this->prophesize(PipelineRepository::class);
        $pipelineRepository
            ->fetch(Argument::type('int'))
            ->willReturn(null)
        ;

        $executionFactory = $this->prophesize(ExecutionFactory::class);
        $executionRepository = $this->prophesize(ExecutionRepository::class);
        $swooleServer = $this->prophesize(SwooleServer::class);

        $executionStartFacade = new ExecutionStartFacade(
            $pipelineRepository->reveal(),
            $executionFactory->reveal(),
            $executionRepository->reveal(),
            $swooleServer->reveal(),
        );

        $this->expectException(UndefinedPipelineException::class);
        $executionStartFacade->start(65);
    }
}
