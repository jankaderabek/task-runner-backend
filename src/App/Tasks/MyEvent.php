<?php

declare(strict_types=1);

namespace App\Tasks;

class MyEvent extends \Symfony\Contracts\EventDispatcher\Event
{
    private int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
