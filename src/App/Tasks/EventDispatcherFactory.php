<?php

declare(strict_types=1);

namespace App\Tasks;

use Interop\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

class EventDispatcherFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * @param string $requestedName
     * @param array<string> $options
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null): EventDispatcher
    {
        $eventDispatcher = new EventDispatcher();
        $eventDispatcher->addSubscriber($container->get(MyEventSubscriber::class));

        return $eventDispatcher;
    }
}
