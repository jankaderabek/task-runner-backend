<?php

declare(strict_types=1);

namespace AppTest\Swoole\Tasks;

use App\Swoole\Tasks\TaskWorker;
use App\Swoole\Tasks\TaskWorkerServerDelegator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Container\ContainerInterface;
use Swoole\Http\Server;

class TaskWorkerServerDelegatorTest extends TestCase
{

    public function testDecorateServerWithAsyncTasksCallbacks(): void
    {
        $taskWorkerServerDelegator = new TaskWorkerServerDelegator();

        $swooleServer = $this->prophesize(Server::class);
        $swooleServer->on('task', Argument::type(TaskWorker::class));
        $swooleServer->on('finish', Argument::type('callable'));

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get(TaskWorker::class)
            ->willReturn($this->prophesize(TaskWorker::class)->reveal())
        ;

        $createdServer = $taskWorkerServerDelegator(
            $container->reveal(),
            Server::class,
            fn() => $swooleServer->reveal(),
        );

        $this->assertInstanceOf(Server::class, $createdServer);
        $swooleServer->on('task', Argument::type(TaskWorker::class))->shouldHaveBeenCalled();
        $swooleServer->on('finish', Argument::type('callable'))->shouldHaveBeenCalled();
    }
}
