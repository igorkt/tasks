<?php

declare(strict_types=1);


namespace Api;

use \ApiTester;

use function PHPUnit\Framework\assertEquals;

final class UpdateTaskCest
{
    public function _before(ApiTester $I): void
    {
        // Code here will be executed before each test.
    }

    public function tryToTest(ApiTester $I): void
    {
        $I->sendPostAsJson('', [
            'title' => 'first task',
            "description" => 'first task description',
            'priority' => 8
        ]);
        $updatedTitle = 'updated first task';
        $tasks = $I->sendGetAsJson('');
        $I->sendPutAsJson('/' . $tasks[0]['id'], ['title' => $updatedTitle]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIsSuccessful();
        $tasks = $I->sendGetAsJson('');
        assertEquals($updatedTitle, $tasks[0]['title']);
    }
}
