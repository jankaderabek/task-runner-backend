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

        $server->on('open', function (\Swoole\WebSocket\Server $server, \Swoole\Http\Request $request) {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

            $server->push($frame->fd, "This message is from swoole websocket server.");
        });

        $server->on('close', function (\Swoole\WebSocket\Server $server, int $fd) {
            echo "client {$fd} closed\n";
        });

        return $server;
    }
}
