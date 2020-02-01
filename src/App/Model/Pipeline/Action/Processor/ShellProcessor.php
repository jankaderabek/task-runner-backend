<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Action\Processor;

use App\Model\Pipeline\Action\Entity\Action;
use Psr\Log\LoggerInterface;

class ShellProcessor
{
    public function process(Action $action): string
    {
        return \shell_exec($action->getCommand());
    }
}
