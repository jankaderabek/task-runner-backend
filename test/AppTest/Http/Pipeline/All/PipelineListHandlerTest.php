<?php

declare(strict_types=1);

namespace AppTest\Http\Pipeline\All;

use App\Http\Pipeline\All\Marshaller\PipelineDataMarshaller;
use App\Http\Pipeline\All\PipelineListHandler;
use App\Model\Pipeline\Entity\Pipeline;
use App\Model\Pipeline\Repository\PipelineRepository;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;

class PipelineListHandlerTest extends TestCase
{

    public function testHandle(): void
    {
        $pipelineRepository = $this->prophesize(PipelineRepository::class);
        $pipelineRepository
            ->findAll()
            ->willReturn([
                $this->prophesize(Pipeline::class),
                $this->prophesize(Pipeline::class),
            ]);

        $pipelineDataMarshaller = $this->prophesize(PipelineDataMarshaller::class);
        $pipelineDataMarshaller
            ->marshall(Argument::type(Pipeline::class))
            ->willReturn(
                [
                    'name' => 'pipeline1',
                ],
            );

        $pipelineListHandler = new PipelineListHandler(
            $pipelineRepository->reveal(),
            $pipelineDataMarshaller->reveal(),
        );

        $response = $pipelineListHandler->handle(new ServerRequest());
        $this->assertInstanceOf(JsonResponse::class, $response);

        $responseData = json_decode($response->getBody()->getContents(), true);
        $this->assertCount(2, $responseData);
        $this->assertEquals('pipeline1', current($responseData)['name']);
    }
}
