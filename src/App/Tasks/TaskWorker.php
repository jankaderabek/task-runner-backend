<?php

declare(strict_types=1);

namespace App\Tasks;

class TaskWorker
{

    /**
     * @var \Psr\EventDispatcher\EventDispatcherInterface
     */
    private \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher;


    public function __construct(
        \Psr\EventDispatcher\EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }


    public function __invoke(
        \Swoole\Http\Server $server,
        int $taskId,
        int $fromId,
        $data
    ): void {
        $this->eventDispatcher->dispatch($data);

        $server->finish('');
    }
}
