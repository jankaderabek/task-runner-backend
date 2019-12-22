<?php

namespace App\Model\Pipeline\Action\Repository;

use App\Model\Pipeline\Action\Entity\Action;

interface ActionRepository
{

    public function fetch(int $id): ?Action;

    /**
     * @return array<Action>
     */
    public function findAll(): array;
}
