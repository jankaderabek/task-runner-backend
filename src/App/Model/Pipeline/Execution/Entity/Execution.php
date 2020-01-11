<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Execution\Entity;

use App\Model\Pipeline\Entity\Pipeline;
use Ramsey\Uuid\UuidInterface;

interface Execution
{
    public function getUuid(): UuidInterface;

    public function getPipeline(): Pipeline;
}
