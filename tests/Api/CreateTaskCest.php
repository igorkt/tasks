<?php

declare(strict_types=1);


namespace Api;

use \ApiTester;

final class CreateTaskCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function tryToTest(ApiTester $I): void
    {
        $this->validation($I);
        $I->sendPostAsJson('', [
            'title' => 'first task',
            "description" => 'first task description',
            'priority' => 8
        ]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseMatchesJsonType([
            'id' => 'integer',
            'title' => 'string',
            'description' => 'string',
            'priority' => 'integer',
            'created_at' => 'string',
        ]);
    }

    public function validation(ApiTester $I): void
    {
        $I->sendPostAsJson('', ['title' => 'old name']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsClientError();
        $I->sendPostAsJson('', ['priority' => 8]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsClientError();
    }
}
