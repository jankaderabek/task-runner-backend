<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Entity;

interface Pipeline
{

    public function getId(): int;

    public function getName(): string;

    /**
     * @return array<\App\Model\Pipeline\Action\Entity\Action>
     */
    public function getActions(): array;
}
