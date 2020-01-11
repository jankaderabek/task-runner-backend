<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Event;

use App\Model\Pipeline\Execution\Entity\Execution;
use Symfony\Contracts\EventDispatcher\Event;

final class ExecutionCreated extends Event
{

    private Execution $execution;

    public function __construct(Execution $execution)
    {
        $this->execution = $execution;
    }

    public function getExecution(): Execution
    {
        return $this->execution;
    }
}
