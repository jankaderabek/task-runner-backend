<?php

declare(strict_types=1);

namespace App;

use App\Http\Pipeline\All\Marshaller\ActionDataMarshaller;
use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Http\Pipeline\All\PipelineListHandler;
use App\Model\Pipeline\Action\Repository\ActionRepository;
use App\Model\Pipeline\Action\Repository\MemoryActionRepository;
use App\Model\Pipeline\Repository\MemoryPipelineRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Zend\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;

/**
 * The configuration provider for the App module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * @return array<string, array<string, array<class-string, array<int,string>|class-string>>>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    /**
     * @return array<string, array<class-string,array<int, string>|class-string>>
     */
    public function getDependencies(): array
    {
        return [
            'invokables' => [
                Handler\PingHandler::class => Handler\PingHandler::class,
            ],
            'factories'  => [
                Handler\HomePageHandler::class => ReflectionBasedAbstractFactory::class,
                \App\Tasks\TaskWorker::class => ReflectionBasedAbstractFactory::class,
                EventDispatcher::class => \App\Tasks\EventDispatcherFactory::class,
                \App\Tasks\MyEventSubscriber::class => ReflectionBasedAbstractFactory::class,
                \Swoole\WebSocket\Server::class => \App\Tasks\SwooleServerFactory::class,
                MemoryActionRepository::class => ReflectionBasedAbstractFactory::class,
                MemoryPipelineRepository::class => ReflectionBasedAbstractFactory::class,
                PipelineListHandler::class => ReflectionBasedAbstractFactory::class,
                ActionDataMarshaller::class => ReflectionBasedAbstractFactory::class,
                PipelineDataMarshaller::class => ReflectionBasedAbstractFactory::class,
            ],
            'delegators' => [
                \Swoole\Http\Server::class => [
                    \App\Tasks\TaskWorkerDelegator::class,
                ],
            ],
            'aliases' => [
               \Psr\EventDispatcher\EventDispatcherInterface::class => EventDispatcher::class,
                ActionRepository::class => MemoryActionRepository::class,
                PipelineRepository::class => MemoryPipelineRepository::class,
            ],
        ];
    }
}
