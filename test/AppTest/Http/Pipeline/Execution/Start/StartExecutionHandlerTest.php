<?php

declare(strict_types=1);

namespace AppTest\Http\Pipeline\Execution\Start;

use App\Http\Pipeline\Execution\Start\Exception\UndefinedPipelineException;
use App\Http\Pipeline\Execution\Start\Facade\ExecutionStartFacade;
use App\Http\Pipeline\Execution\Start\StartExecutionHandler;
use App\Model\Pipeline\Execution\Entity\Execution;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Ramsey\Uuid\Uuid;

class StartExecutionHandlerTest extends TestCase
{

    public function testHandle(): void
    {
        $execution = $this->prophesize(Execution::class);
        $uuid = Uuid::uuid4();

        $execution
            ->getUuid()
            ->willReturn($uuid)
        ;

        $executionStarter = $this->prophesize(ExecutionStartFacade::class);
        $executionStarter
            ->start(Argument::type('int'))
            ->willReturn($execution->reveal())
        ;

        $addExecutionHandler = new StartExecutionHandler($executionStarter->reveal());

        $serverRequest = new ServerRequest();

        $response = $addExecutionHandler->handle($serverRequest->withAttribute('pipelineId', "321"));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getBody()->getContents(), true);
        $this->assertTrue(isset($responseData['uuid']));
        $this->assertEquals($uuid->toString(), $responseData['uuid']);
    }

    public function testHandleExecutionOfUndefinedPipeline(): void
    {
        $execution = $this->prophesize(Execution::class);
        $uuid = Uuid::uuid4();

        $execution
            ->getUuid()
            ->willReturn($uuid)
        ;

        $executionStarter = $this->prophesize(ExecutionStartFacade::class);
        $executionStarter
            ->start(Argument::type('int'))
            ->willThrow(UndefinedPipelineException::class)
        ;

        $addExecutionHandler = new StartExecutionHandler($executionStarter->reveal());

        $serverRequest = new ServerRequest();

        $response = $addExecutionHandler->handle($serverRequest->withAttribute('pipelineId', "321"));
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(404, $response->getStatusCode());
    }
}
