<?php

declare(strict_types=1);

namespace AppTest\Http\Pipeline\All\Marshaller;

use App\Http\Pipeline\All\Marshaller\ActionDataMarshaller;
use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Entity\Pipeline;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class PipelineDataMarshallerTest extends TestCase
{

    public function testMarshall(): void
    {
        $actionDataMarshaller = $this->prophesize(ActionDataMarshaller::class);
        $actionDataMarshaller
            ->marshall(Argument::type(Action::class))
            ->willReturn(['id' => 145])
        ;

        $pipelineDataMarshaller = new PipelineDataMarshaller($actionDataMarshaller->reveal());

        $pipeline = $this->prophesize(Pipeline::class);
        $pipeline->getId()->willReturn(456);
        $pipeline->getName()->willReturn('Test pipeline');
        $pipeline->getActions()->willReturn([
            $this->prophesize(Action::class)->reveal(),
            $this->prophesize(Action::class)->reveal(),
        ]);


        $marshalledPipeline = $pipelineDataMarshaller->marshall($pipeline->reveal());

        $this->assertEquals(456, $marshalledPipeline['id']);
        $this->assertEquals('Test pipeline', $marshalledPipeline['name']);
        $this->assertCount(2, $marshalledPipeline['actions']);
        $this->assertEquals(145, current($marshalledPipeline['actions'])['id']);
    }
}
