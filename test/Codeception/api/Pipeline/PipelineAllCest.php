<?php

namespace Pipeline;

class PipelineAllCest
{

    public function tryLoadAllPipelines(\ApiTester $I): void
    {
        $I->sendGET('/pipelines');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesJsonPath('$[0].id');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'actions' => 'array',
        ], '$[0]');

        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'name' => 'string',
            'type' => 'string',
            'command' => 'string',
        ], '$[0].actions[0]');
    }
}
