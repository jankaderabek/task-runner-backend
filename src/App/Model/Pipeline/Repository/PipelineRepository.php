<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Repository;

use App\Model\Pipeline\Entity\Pipeline;

interface PipelineRepository
{

    public function fetch(int $id): ?Pipeline;

    /**
     * @return array<Pipeline>
     */
    public function findAll(): array;
}
