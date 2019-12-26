<?php

declare(strict_types=1);

namespace AppTest\Swoole\Tasks;

use App\Swoole\Tasks\TaskWorker;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\EventDispatcher\EventDispatcherInterface;
use Swoole\Http\Server;
use Symfony\Contracts\EventDispatcher\Event;

class TaskWorkerTest extends TestCase
{

    public function testTaskProcess(): void
    {
        $eventDispatcher = $this->prophesize(EventDispatcherInterface::class);
        $eventDispatcher
            ->dispatch(Argument::type(Event::class))
        ;

        $taskWorker = new TaskWorker($eventDispatcher->reveal());

        $swooleServer = $this->prophesize(Server::class);
        $swooleServer
            ->finish()
        ;

        $event = $this->prophesize(Event::class);

        $taskWorker($swooleServer->reveal(), 1, 2, $event->reveal());

        $eventDispatcher->dispatch(Argument::type(Event::class))->shouldHaveBeenCalled();
        $swooleServer->finish('')->shouldHaveBeenCalled();
    }
}
