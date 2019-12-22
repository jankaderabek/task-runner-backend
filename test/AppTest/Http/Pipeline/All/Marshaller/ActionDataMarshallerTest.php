<?php

namespace AppTest\Http\Pipeline\All\Marshaller;

use App\Http\Pipeline\All\Marshaller\ActionDataMarshaller;
use App\Model\Pipeline\Action\Entity\Action;
use PHPUnit\Framework\TestCase;

class ActionDataMarshallerTest extends TestCase
{
    public function testMarshall(): void
    {
        $actionDataMarshaller = new ActionDataMarshaller();

        $action = $this->prophesize(Action::class);
        $action->getId()->willReturn(123);
        $action->getName()->willReturn('Test action');
        $action->getType()->willReturn('ssh');
        $action->getCommand()->willReturn('ls');

        $marshalledAction = $actionDataMarshaller->marshall($action->reveal());

        $this->assertEquals(123, $marshalledAction['id']);
        $this->assertEquals('Test action', $marshalledAction['name']);
        $this->assertEquals('ssh', $marshalledAction['type']);
        $this->assertEquals('ls', $marshalledAction['command']);
    }
}
