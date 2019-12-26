<?php

declare(strict_types=1);

namespace App\Swoole\WebSocket;

use Swoole\WebSocket\Server as WebSocketServer;

class WebSocketServerDelegator
{

    /**
     * @param array<string> $options
     */
    public function __invoke(
        \Psr\Container\ContainerInterface $container,
        string $name,
        callable $callback,
        ?array $options = null
    ): \Swoole\Http\Server {
        $server = $callback();
        \assert($server instanceof \Swoole\Http\Server);

        $server->on('open', function (WebSocketServer $server, \Swoole\Http\Request $request): void {
            echo "server: handshake success with fd{$request->fd}\n";
        });

        $server->on('message', function (WebSocketServer $server, \Swoole\WebSocket\Frame $frame): void {
            echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";

            $server->push($frame->fd, "This message is from swoole websocket server.");
        });

        $server->on('close', function (WebSocketServer $server, int $fd): void {
            echo "client {$fd} closed\n";
        });

        return $server;
    }
}
