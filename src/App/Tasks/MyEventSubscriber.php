<?php

declare(strict_types=1);

namespace App\Tasks;

class MyEventSubscriber implements \Symfony\Component\EventDispatcher\EventSubscriberInterface
{

    /**
     * @return array<string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            MyEvent::class => 'onMyEvent',
        ];
    }

    public function onMyEvent(MyEvent $event): void
    {
        // fetch event information here
        echo "DemoListener is called!\n";
        \sleep(5);
        echo "The value of the foo is :" . $event->getId() . "\n";
    }
}
