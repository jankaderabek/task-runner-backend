<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Entity;

use App\Model\Pipeline\Action\Entity\Action;

class SimplePipeline implements Pipeline
{
    private int $id;

    private string $name;

    /**
     * @var array<Action>
     */
    private array $actions;

    public function __construct(
        int $id,
        string $name,
        Action ...$actions
    ) {

        $this->id = $id;
        $this->name = $name;
        $this->actions = $actions;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getActions(): array
    {
        return $this->actions;
    }
}
