<?php

declare(strict_types=1);

namespace App\Tasks;

class TaskWorkerDelegator
{

    /**
     * @param array<string> $options
     */
    public function __invoke(
        \Psr\Container\ContainerInterface $container,
        string $name,
        callable $callback,
        array $options = null
    ): \Swoole\Http\Server {
        /** @var \Swoole\Http\Server $server */
        $server = $callback();

        $server->on('task', $container->get(\App\Tasks\TaskWorker::class));
        $server->on('finish', function ($server, $taskId, $data) {
        });

        return $server;
    }
}
