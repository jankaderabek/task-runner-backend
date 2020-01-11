<?php

declare(strict_types=1);

namespace App\Http\Pipeline\Execution\Start;

use App\Http\Pipeline\Execution\Start\Facade\ExecutionStartFacade;
use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class StartExecutionHandler implements RequestHandlerInterface
{

    private ExecutionStartFacade $executionStarter;

    public function __construct(
        ExecutionStartFacade $executionStarter
    ) {
        $this->executionStarter = $executionStarter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pipelineId = (int) $request->getAttribute('pipelineId');

        try {
            $execution = $this->executionStarter->start($pipelineId);
        } catch (Exception\UndefinedPipelineException $e) {
            return new JsonResponse(
                'Undefined pipeline',
                404,
            );
        }

        return new JsonResponse([
            'uuid' => $execution->getUuid(),
        ]);
    }
}
