<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Event;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ExecutionCreatedSubscriber implements EventSubscriberInterface
{

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [ExecutionCreated::class => 'process'];
    }

    public function process(ExecutionCreated $executionCreated): void
    {
        $pipeline = $executionCreated->getExecution()->getPipeline();

        foreach ($pipeline->getActions() as $action) {
            \var_dump($action);
        }
    }
}
