<?php

declare(strict_types=1);

namespace App\Swoole\Server;

use Psr\Container\ContainerInterface;
use Swoole\Runtime as SwooleRuntime;

class SwooleServerFactory
{
    public const DEFAULT_HOST = '127.0.0.1';
    public const DEFAULT_PORT = 8080;

    /**
     * Swoole server supported modes
     */
    private const MODES = [
        SWOOLE_BASE,
        SWOOLE_PROCESS,
    ];

    /**
     * Swoole server supported protocols
     */
    private const PROTOCOLS = [
        SWOOLE_SOCK_TCP,
        SWOOLE_SOCK_TCP6,
        SWOOLE_SOCK_UDP,
        SWOOLE_SOCK_UDP6,
        SWOOLE_UNIX_DGRAM,
        SWOOLE_UNIX_STREAM,
    ];

    /**
     * @see https://www.swoole.co.uk/docs/modules/swoole-server-methods#swoole_server-__construct
     * @see https://www.swoole.co.uk/docs/modules/swoole-server/predefined-constants for $mode and $protocol constant
     * @throws \Mezzio\Swoole\Exception\InvalidArgumentException for invalid $port values
     * @throws \Mezzio\Swoole\Exception\InvalidArgumentException for invalid $mode values
     * @throws \Mezzio\Swoole\Exception\InvalidArgumentException for invalid $protocol values
     */
    public function __invoke(ContainerInterface $container): \Swoole\WebSocket\Server
    {
        $config = $container->get('config');
        $swooleConfig = $config['mezzio-swoole'] ?? [];
        $serverConfig = $swooleConfig['swoole-http-server'] ?? [];

        $host = $serverConfig['host'] ?? self::DEFAULT_HOST;
        $port = $serverConfig['port'] ?? self::DEFAULT_PORT;
        $mode = $serverConfig['mode'] ?? SWOOLE_BASE;
        $protocol = $serverConfig['protocol'] ?? SWOOLE_SOCK_TCP;

        if ($port < 1 || $port > 65535) {
            throw new \Mezzio\Swoole\Exception\InvalidArgumentException('Invalid port');
        }

        if (!in_array($mode, self::MODES, true)) {
            throw new \Mezzio\Swoole\Exception\InvalidArgumentException('Invalid server mode');
        }

        $validProtocols = self::PROTOCOLS;
        if (defined('SWOOLE_SSL')) {
            $validProtocols[] = SWOOLE_SOCK_TCP | SWOOLE_SSL;
            $validProtocols[] = SWOOLE_SOCK_TCP6 | SWOOLE_SSL;
        }

        if (!in_array($protocol, $validProtocols, true)) {
            throw new \Mezzio\Swoole\Exception\InvalidArgumentException('Invalid server protocol');
        }

        $enableCoroutine = $swooleConfig['enable_coroutine'] ?? false;
        if ($enableCoroutine && method_exists(SwooleRuntime::class, 'enableCoroutine')) {
            SwooleRuntime::enableCoroutine(true);
        }

        $httpServer = new \Swoole\WebSocket\Server($host, $port, $mode, $protocol);
        $serverOptions = $serverConfig['options'] ?? [];
        $httpServer->set($serverOptions);


        return $httpServer;
    }
}
