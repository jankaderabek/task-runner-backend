<?php

namespace Pipeline;

class StartExecutionCest
{

    public function tryToStartPipelineExecution(\ApiTester $I): void
    {
        $I->sendPOST('/pipelines/1/executions');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();

        $I->seeResponseMatchesJsonType([
            'uuid' => 'string',
        ]);
    }

    public function tryToStartExecutionOfUndefinedPipeline(\ApiTester $I): void
    {
        $I->sendPOST('/pipelines/999/executions');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::NOT_FOUND);
        $I->seeResponseIsJson();
    }
}
