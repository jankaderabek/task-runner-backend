<?php

namespace App\Model\Pipeline\Action\Entity;

interface Action
{

    public const TYPE_SSH = 'ssh';
    public const TYPE_LOCAL_TERMINAL = 'local_terminal';

    public function getId(): int;

    public function getName(): string;

    public function getType(): string;

    public function getCommand(): string;
}
