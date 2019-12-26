<?php

declare(strict_types=1);

namespace AppTest\Model\Pipeline\Action\Entity;

use App\Model\Pipeline\Action\Entity\SimpleAction;
use PHPUnit\Framework\TestCase;

class SimpleActionTest extends TestCase
{

    public function testCreate(): void
    {
        $simpleAction = new SimpleAction(321, 'My action', 'ssh', 'pwd');

        $this->assertEquals(321, $simpleAction->getId());
        $this->assertEquals('My action', $simpleAction->getName());
        $this->assertEquals('ssh', $simpleAction->getType());
        $this->assertEquals('pwd', $simpleAction->getCommand());
    }
}
