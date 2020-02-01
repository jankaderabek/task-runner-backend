<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Entity;

use App\Model\Pipeline\Action\Entity\Action;

interface Pipeline
{

    public function getId(): int;

    public function getName(): string;

    /**
     * @return array<Action>|Action[]
     */
    public function getActions(): array;
}
