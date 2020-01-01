<?php

declare(strict_types=1);

namespace App\Events;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Initializer\InitializerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

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
