<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Event;

use App\Model\Pipeline\Execution\Processor\ExecutionProcessor;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

final class ExecutionCreatedSubscriber implements EventSubscriberInterface
{

    private ExecutionProcessor $executionProcessor;

    public function __construct(ExecutionProcessor $executionProcessor)
    {
        $this->executionProcessor = $executionProcessor;
    }


    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [ExecutionCreated::class => 'process'];
    }

    public function process(ExecutionCreated $executionCreated): void
    {
        $this->executionProcessor->process($executionCreated->getExecution());
    }
}
