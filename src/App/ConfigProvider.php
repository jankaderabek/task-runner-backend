<?php

declare(strict_types=1);

namespace App;

use App\Events\DispatcherInitializer;
use App\Http\Pipeline\All\Marshaller\ActionDataMarshaller;
use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Http\Pipeline\All\PipelineListHandler;
use App\Model\Pipeline\Action\Repository\ActionRepository;
use App\Model\Pipeline\Action\Repository\MemoryActionRepository;
use App\Model\Pipeline\Repository\MemoryPipelineRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use App\Swoole\Server\SwooleServerFactory;
use App\Swoole\Tasks\TaskWorker;
use App\Swoole\Tasks\TaskWorkerServerDelegator;
use App\Swoole\WebSocket\WebSocketServerDelegator;
use App\Tasks\MyEventSubscriber;
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
     * @return array<mixed>
     */
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'subscribers' => [
                MyEventSubscriber::class,
            ],
        ];
    }

    /**
     * @return  array<mixed>
     */
    public function getDependencies(): array
    {
        return [
            'initializers' => [
                DispatcherInitializer::class,
            ],
            'factories'  => [
                TaskWorker::class => ReflectionBasedAbstractFactory::class,
                EventDispatcher::class => ReflectionBasedAbstractFactory::class,
                MyEventSubscriber::class => ReflectionBasedAbstractFactory::class,
                \Swoole\WebSocket\Server::class => SwooleServerFactory::class,
                MemoryActionRepository::class => ReflectionBasedAbstractFactory::class,
                MemoryPipelineRepository::class => ReflectionBasedAbstractFactory::class,
                PipelineListHandler::class => ReflectionBasedAbstractFactory::class,
                ActionDataMarshaller::class => ReflectionBasedAbstractFactory::class,
                PipelineDataMarshaller::class => ReflectionBasedAbstractFactory::class,
            ],
            'delegators' => [
                \Swoole\Http\Server::class => [
                    TaskWorkerServerDelegator::class,
                    WebSocketServerDelegator::class,
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
