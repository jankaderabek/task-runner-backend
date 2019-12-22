<?php

namespace AppTest\Model\Pipeline\Entity;

use App\Model\Pipeline\Action\Entity\Action;
use App\Model\Pipeline\Entity\SimplePipeline;
use PHPUnit\Framework\TestCase;

class SimplePipelineTest extends TestCase
{

    public function testCreate(): void
    {
        $pipeline = new SimplePipeline(
            12,
            'Name',
            $this->prophesize(Action::class)->reveal(),
            $this->prophesize(Action::class)->reveal(),
            $this->prophesize(Action::class)->reveal(),
        );

        $this->assertEquals(12, $pipeline->getId());
        $this->assertEquals('Name', $pipeline->getName());
        $this->assertCount(3, $pipeline->getActions());
        $this->assertInstanceOf(Action::class, \current($pipeline->getActions()));
    }
}
