<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Processor;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Action\Processor\ShellProcessor;
use App\Model\Pipeline\Execution\Entity\Execution;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Swoole\Exception;

class ExecutionProcessor
{

    private ShellProcessor $shellProcessor;

    private LoggerInterface $logger;

    public function __construct(
        ShellProcessor $shellProcessor
    ) {
        $this->shellProcessor = $shellProcessor;

        $log = new Logger('name');
        $log->pushHandler(new RotatingFileHandler('log/execution/execution.log'));
        $this->logger = $log;
    }

    public function process(Execution $execution): void
    {
        $this->logger->info('Execution started', ['execution' => $execution->getUuid()->toString()]);

        foreach ($execution->getPipeline()->getActions() as $action) {
            $this->logger->info(\sprintf('[%s] Executing "%s"', $action->getType(), $action->getCommand()), [
                'action' => $action->getId(),
                'actionType' => $action->getType(),
            ]);

            switch ($action->getType()) {
                case Action::TYPE_LOCAL_TERMINAL:
                    $this->logger->info(\sprintf('Result: %s', $this->shellProcessor->process($action)));
                    break;

                default:
                    throw new Exception();
            }

            $this->logger->info('Execution of action finished', ['action' => $action->getId()]);
        }

        $this->logger->info('Execution finished', ['execution' => $execution->getUuid()->toString()]);
    }
}
