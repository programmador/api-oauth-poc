<?php

namespace App\Tests;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GrantScopeCest
{
    public function _before(ApiTester $I)
    {
    }

    public function _after(ApiTester $I)
    {
    }

    public function testNonJsonRequestFails(ApiTester $I)
    {
        $request = ['non json' => 'request'];
        $I->sendPOST('grant', $request);    // no json_encode() call
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseContainsJson(["expecting json for input"]);
    }

    public function testRequestWithoutUsernameFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'password' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseContainsJson(["expecting 'username' 'password' and 'scope' in input json"]);
    }

    public function testRequestWithoutPasswordFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'username' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseContainsJson(["expecting 'username' 'password' and 'scope' in input json"]);
    }

    public function testRequestWithoutScopeFails(ApiTester $I)
    {
        $request = ['username' => 'a', 'password' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseContainsJson(["expecting 'username' 'password' and 'scope' in input json"]);
    }

    public function testRequestWithNonsenseUsernameFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'username' => 'b', 'password' => 'c'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN); // 403
        $I->seeResponseContainsJson(["wrong 'username' or 'password'"]);
    }

    public function testRequestWithWrongPasswordFails(ApiTester $I)
    {
        $userFixture = $I->getUserFixtures()[0];
        $request = [
            'scope' => 'any_scope',
            'username' => $userFixture['name'],
            'password' => $userFixture['password'] . 'wrong_password'
        ];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN); // 403
        $I->seeResponseContainsJson(["wrong 'username' or 'password'"]);
    }
}
