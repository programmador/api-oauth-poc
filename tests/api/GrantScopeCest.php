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
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting json for input",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithoutUsernameFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'password' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting 'username' 'password' and 'scope' in input json",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithoutPasswordFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'username' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting 'username' 'password' and 'scope' in input json",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithoutScopeFails(ApiTester $I)
    {
        $request = ['username' => 'a', 'password' => 'b'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting 'username' 'password' and 'scope' in input json",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithNonsenseUsernameFails(ApiTester $I)
    {
        $request = ['scope' => 'a', 'username' => 'b', 'password' => 'c'];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::FORBIDDEN); // 403
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::FORBIDDEN,
                'message' => 'Forbidden',
                'exception' => [
                    [
                        'message' => "wrong 'username' or 'password'",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
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
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::FORBIDDEN,
                'message' => 'Forbidden',
                'exception' => [
                    [
                        'message' => "wrong 'username' or 'password'",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithValidCredentialsSuccess(ApiTester $I)
    {
        $userFixture = $I->getUserFixtures()[0];
        $request = [
            'scope'     => 'any_scope',
            'username'  => $userFixture['name'],
            'password'  => $userFixture['password']
        ];
        $I->sendPOST('grant', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'access_token'  => 'string',
            'mac_key'       => 'string',
            'expires_in'    => 'integer',
            'scope'         => 'string',
            'token_type'    => 'string',
        ]);
    }

    /* @todo test response values:
       - token_type is actually constant
       - access_token and mac_key should be distinct and should have specific length
       - access_token and mac_key should have specific length from a config
       - expires should have specific value from a config
    */
}
