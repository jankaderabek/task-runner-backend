<?php

declare(strict_types=1);

namespace App;

use App\Events\DispatcherInitializer;
use App\Http\Pipeline\All\Marshaller\ActionDataMarshaller;
use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Http\Pipeline\All\PipelineListHandler;
use App\Http\Pipeline\Execution\Start\Facade\ExecutionStartFacade;
use App\Http\Pipeline\Execution\Start\StartExecutionHandler;
use App\Model\Pipeline\Action\Repository\ActionRepository;
use App\Model\Pipeline\Action\Repository\MemoryActionRepository;
use App\Model\Pipeline\Execution\Entity\ExecutionFactory;
use App\Model\Pipeline\Execution\Event\ExecutionCreatedSubscriber;
use App\Model\Pipeline\Execution\Repository\ExecutionRepository;
use App\Model\Pipeline\Execution\Repository\RedisExecutionRepository;
use App\Model\Pipeline\Repository\MemoryPipelineRepository;
use App\Model\Pipeline\Repository\PipelineRepository;
use App\Redis\RedisClient;
use App\Redis\RedisClientFactory;
use App\Swoole\Server\SwooleServerFactory;
use App\Swoole\Tasks\TaskWorker;
use App\Swoole\Tasks\TaskWorkerServerDelegator;
use App\Tasks\MyEventSubscriber;
use Laminas\ServiceManager\AbstractFactory\ReflectionBasedAbstractFactory;
use Symfony\Component\EventDispatcher\EventDispatcher;

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
                ExecutionCreatedSubscriber::class,
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
                ExecutionStartFacade::class => ReflectionBasedAbstractFactory::class,
                StartExecutionHandler::class => ReflectionBasedAbstractFactory::class,
                ExecutionFactory::class => ReflectionBasedAbstractFactory::class,
                RedisExecutionRepository::class => ReflectionBasedAbstractFactory::class,
                RedisClient::class => RedisClientFactory::class,
                ExecutionCreatedSubscriber::class => ReflectionBasedAbstractFactory::class,
            ],
            'delegators' => [
                \Swoole\Http\Server::class => [
                    TaskWorkerServerDelegator::class,
//                    WebSocketServerDelegator::class,
                ],
            ],
            'aliases' => [
               \Psr\EventDispatcher\EventDispatcherInterface::class => EventDispatcher::class,
                ActionRepository::class => MemoryActionRepository::class,
                PipelineRepository::class => MemoryPipelineRepository::class,
                ExecutionRepository::class => RedisExecutionRepository::class,
            ],
        ];
    }
}
