<?php

declare(strict_types=1);

namespace App\Swoole\Tasks;

use Psr\EventDispatcher\EventDispatcherInterface;
use Swoole\Http\Server;
use Symfony\Contracts\EventDispatcher\Event;

class TaskWorker
{

    private EventDispatcherInterface $eventDispatcher;


    public function __construct(
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
    }


    public function __invoke(
        Server $server,
        int $taskId,
        int $fromId,
        Event $data
    ): void {
        $this->eventDispatcher->dispatch($data);

        $server->finish('');
    }
}
