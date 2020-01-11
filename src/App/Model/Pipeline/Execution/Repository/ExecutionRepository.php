<?php

namespace App\Model\Pipeline\Execution\Repository;

use App\Model\Pipeline\Execution\Entity\Execution;
use Ramsey\Uuid\UuidInterface;

interface ExecutionRepository
{

    public function fetch(UuidInterface $uuid): ?Execution;

    public function save(Execution $execution): void;
}
