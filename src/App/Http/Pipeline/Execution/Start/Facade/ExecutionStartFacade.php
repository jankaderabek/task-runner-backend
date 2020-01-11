<?php

declare(strict_types=1);

namespace App\Http\Pipeline\Execution\Start\Facade;

use App\Http\Pipeline\Execution\Start\Exception\UndefinedPipelineException;
use App\Model\Pipeline\Execution\Entity\Execution;
use App\Model\Pipeline\Execution\Entity\ExecutionFactory;
use App\Model\Pipeline\Execution\Event\ExecutionCreated;
use App\Model\Pipeline\Execution\Repository\ExecutionRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use Swoole\Http\Server;

class ExecutionStartFacade
{

    private PipelineRepository $pipelineRepository;

    private ExecutionFactory $executionFactory;

    private ExecutionRepository $executionRepository;

    private Server $swooleServer;

    public function __construct(
        PipelineRepository $pipelineRepository,
        ExecutionFactory $executionFactory,
        ExecutionRepository $executionRepository,
        Server $swooleServer
    ) {
        $this->pipelineRepository = $pipelineRepository;
        $this->executionFactory = $executionFactory;
        $this->executionRepository = $executionRepository;
        $this->swooleServer = $swooleServer;
    }

    /**
     * @throws UndefinedPipelineException
     */
    public function start(int $pipelineId): Execution
    {
        $pipeline = $this->pipelineRepository->fetch($pipelineId);

        if (!$pipeline) {
            throw new UndefinedPipelineException();
        }

        $execution = $this->executionFactory->create($pipeline);
        $this->executionRepository->save($execution);

        $this->swooleServer->task(new ExecutionCreated($execution));

        return $execution;
    }
}
