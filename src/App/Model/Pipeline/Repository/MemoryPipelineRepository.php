<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Repository;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Action\Repository\ActionRepository;
use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Entity\SimplePipeline;

class MemoryPipelineRepository implements PipelineRepository
{

    /**
     * @var array<Pipeline>
     */
    private array $pipelines;

    public function __construct(
        ActionRepository $actionRepository
    ) {
        $action1 = $actionRepository->fetch(123);
        \assert($action1 instanceof Action);

        $action2 = $actionRepository->fetch(456);
        \assert($action2 instanceof Action);

        $this->pipelines = [
            1 => new SimplePipeline(
                1,
                'Pipeline with remote action',
                $action1,
            ),
            2 => new SimplePipeline(
                2,
                'Pipeline with local action',
                $action2,
            ),
        ];
    }

    public function fetch(int $id): ?Pipeline
    {
        return $this->pipelines[$id] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->pipelines;
    }
}
