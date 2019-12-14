<?php

declare(strict_types=1);

namespace App\Handler;

class HomePageHandler implements \Psr\Http\Server\RequestHandlerInterface
{

    /**
     * @var \Swoole\Http\Server
     */
    private $httpServer;


    public function __construct(
        \Swoole\Http\Server $httpServer
    ) {
        $this->httpServer = $httpServer;
    }

    public function handle(\Psr\Http\Message\ServerRequestInterface $request): \Psr\Http\Message\ResponseInterface
    {
        $this->httpServer->task(new \App\Tasks\MyEvent(\random_int(1, 5000)));

        return new \Zend\Diactoros\Response\JsonResponse([
            'welcome' => 'Congratulations! You have installed the zend-expressive skeleton application.',
            'docsUrl' => 'https://docs.zendframework.com/zend-expressive/',
        ]);
    }
}
