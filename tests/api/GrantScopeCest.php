<?php
namespace App\Tests;
use App\Tests\ApiTester;

class GrantScopeCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    // tests
    public function testNonJsonRequestFails(ApiTester $I)
    {
        $I->sendPOST('grant', ['a' => 'b']);
        $I->dumpResponse();
        $I->seeResponseCodeIs(\Codeception\Util\HttpCode::OK); // 200
    }
}
