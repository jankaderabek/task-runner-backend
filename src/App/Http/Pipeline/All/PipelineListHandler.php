<?php

declare(strict_types=1);

namespace App\Http\Pipeline\All;

use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Repository\PipelineRepository;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Tightenco\Collect\Support\Collection;
use Zend\Diactoros\Response\JsonResponse;

class PipelineListHandler implements RequestHandlerInterface
{

    private PipelineRepository $pipelineRepository;

    private PipelineDataMarshaller $pipelineDataMarshaller;

    public function __construct(
        PipelineRepository $pipelineRepository,
        PipelineDataMarshaller $pipelineDataMarshaller
    ) {
        $this->pipelineRepository = $pipelineRepository;
        $this->pipelineDataMarshaller = $pipelineDataMarshaller;
    }

    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pipelines = Collection::make($this->pipelineRepository->findAll())
            ->map(fn(Pipeline $pipeline): array => $this->pipelineDataMarshaller->marshall($pipeline))
            ->values()
        ;

        return new JsonResponse($pipelines->all());
    }
}
