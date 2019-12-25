<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Action\Repository;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Action\Entity\SimpleAction;

class MemoryActionRepository implements ActionRepository
{

    /**
     * @var array<SimpleAction>
     */
    private array $actions;

    public function __construct()
    {
        $this->actions = [
            123 => new SimpleAction(
                123,
                'Get working directory on remote',
                Action::TYPE_SSH,
                'pwd',
            ),
            456 => new SimpleAction(
                456,
                'Get working directory on local machine',
                Action::TYPE_LOCAL_TERMINAL,
                'pwd',
            ),
        ];
    }


    public function fetch(int $id): ?Action
    {
        return $this->actions[$id] ?? null;
    }

    /**
     * @return array<SimpleAction>
     */
    public function findAll(): array
    {
        return $this->actions;
    }
}
