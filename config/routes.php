<?php

declare(strict_types=1);

use App\Http\Pipeline\All\PipelineListHandler;
use App\Http\Pipeline\Execution\Start\StartExecutionHandler;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->get('/pipelines', PipelineListHandler::class);
    $app
        ->post('/pipelines/{pipelineId}/executions', StartExecutionHandler::class)
    ;
};
