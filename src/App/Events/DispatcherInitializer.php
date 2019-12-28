<?php

declare(strict_types=1);

namespace App\Events;

use Interop\Container\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Zend\ServiceManager\Initializer\InitializerInterface;

class DispatcherInitializer implements InitializerInterface
{

    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $instance)
    {
        if (! $instance instanceof EventSubscriberInterface) {
            return;
        }

        $eventDispatcher = $container->get(EventDispatcher::class);
        \assert($eventDispatcher instanceof EventDispatcher);

        $eventDispatcher->addSubscriber($instance);
    }
}
