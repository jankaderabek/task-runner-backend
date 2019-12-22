<?php

declare(strict_types=1);

namespace App\Model\Pipeline\Action\Entity;

class SimpleAction implements Action
{
    private int $id;

    private string $name;

    private string $type;

    private string $command;

    public function __construct(
        int $id,
        string $name,
        string $type,
        string $command
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->command = $command;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getCommand(): string
    {
        return $this->command;
    }
}
