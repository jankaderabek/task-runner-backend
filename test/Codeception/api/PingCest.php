<?php

namespace Api;

class PingCest
{

    public function tryToTest(\ApiTester $I): void
    {
        $I->sendGET('/api/ping');

        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'ack' => 'integer',
        ]);
    }
}
