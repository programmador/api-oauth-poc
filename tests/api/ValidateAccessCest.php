<?php

namespace App\Tests;

use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class ValidateAccessCest
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
        $I->sendPOST('validate', $request);    // no json_encode() call
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

    public function testRequestWithoutTokenFails(ApiTester $I)
    {
        $request = ['scope' => 'a'];
        $I->sendPOST('validate', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting 'token' and 'scope' in input json",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithoutScopeFails(ApiTester $I)
    {
        $request = ['token' => 'a'];
        $I->sendPOST('validate', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST); // 400
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::BAD_REQUEST,
                'message' => 'Bad Request',
                'exception' => [
                    [
                        'message' => "expecting 'token' and 'scope' in input json",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestWithNonsenseTokenFails(ApiTester $I)
    {
        $request = ['token' => 'a', 'scope' => 'b'];
        $I->sendPOST('validate', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::NOT_FOUND,
                'message' => 'Not Found',
                'exception' => [
                    [
                        'message' => "token not found",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }


    public function testRequestWithNonsenseScopeFails(ApiTester $I)
    {
        $token = $I->getNewToken();
        $request = ['token' => $token->getId(), 'scope' => 'nonsense'];
        $I->sendPOST('validate', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND); // 404
        $I->seeResponseIsJson();
        $expectedResponse = [
            'error' => [
                'code' => HttpCode::NOT_FOUND,
                'message' => 'Not Found',
                'exception' => [
                    [
                        'message' => "token not found",
                    ],
                ],
            ],
        ];
        $I->seeResponseContainsJson($expectedResponse);
    }

    public function testRequestForExistingTokenSuccess(ApiTester $I)
    {
        $token = $I->getNewToken();
        $request = ['token' => $token->getId(), 'scope' => $token->getScope()];
        $I->sendPOST('validate', json_encode($request));
        $I->seeResponseCodeIs(HttpCode::OK); // 200
        $I->seeResponseIsJson();
        $I->seeResponseMatchesJsonType([
            'uid'       => 'integer',
            'mac_key'   => 'string',
        ]);
        $I->seeResponseContainsJson([
            'uid'       => $token->getUid(),
            'mac_key'   => $token->getMacKey(),
        ]);
    }
}
