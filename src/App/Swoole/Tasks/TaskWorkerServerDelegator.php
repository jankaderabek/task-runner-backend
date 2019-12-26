<?php

declare(strict_types=1);

namespace App\Swoole\Tasks;

use Psr\Container\ContainerInterface;
use Swoole\Http\Server;

class TaskWorkerServerDelegator
{

    /**
     * @param array<string> $options
     */
    public function __invoke(
        ContainerInterface $container,
        string $name,
        callable $callback,
        ?array $options = null
    ): Server {
        $server = $callback();
        \assert($server instanceof Server);

        $server->on('task', $container->get(TaskWorker::class));
        $server->on('finish', function ($server, $taskId, $data): void {
        });

        return $server;
    }
}
