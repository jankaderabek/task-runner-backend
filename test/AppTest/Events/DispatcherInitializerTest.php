<?php

declare(strict_types=1);

namespace AppTest\Events;

use App\Events\DispatcherInitializer;
use Interop\Container\ContainerInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class DispatcherInitializerTest extends TestCase
{

    public function testNotCallInitializerForSubscribersRegistration(): void
    {
        $dispatcherInitializer = new DispatcherInitializer();

        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->addSubscriber($this);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get(EventDispatcher::class)
            ->willReturn($eventDispatcher->reveal())
        ;

        $dispatcherInitializer($container->reveal(), $this);
        $container->get(Argument::type('string'))->shouldNotHaveBeenCalled();
    }

    public function testCreateInitializerForSubscribersRegistration(): void
    {
        $dispatcherInitializer = new DispatcherInitializer();

        $event = $this->prophesize(EventSubscriberInterface::class)->reveal();
        $eventDispatcher = $this->prophesize(EventDispatcher::class);
        $eventDispatcher->addSubscriber($event);

        $container = $this->prophesize(ContainerInterface::class);
        $container
            ->get(EventDispatcher::class)
            ->willReturn($eventDispatcher->reveal())
        ;

        $dispatcherInitializer($container->reveal(), $event);

        $eventDispatcher->addSubscriber($event)->shouldHaveBeenCalled($this);
    }
}
