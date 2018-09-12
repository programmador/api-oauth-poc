<?php namespace App\Kernel;

use Httpful\Request;
use StdClass;

class BuenasDias
{
    public function run()
    {
        $username = "Smorfily";
        $password = "Smurf6";
        $scope = "movies";

        $grantResponse = $this->getGrantResponse($username, $password, $scope);

        $token = $grantResponse->access_token;
        $scope = $grantResponse->scope;

        $validateResponse = $this->getValidateResponse($token, $scope);
        $uid = $validateResponse->uid;
        $key = $validateResponse->mac_key;
        echo "Now movie backend is permitted to process data manipulation request at resource"
            . " '{$scope}' for user #{$uid} and validate the request which should have been signed"
            . " by frontend with key {$key}";
    }

    private function getGrantResponse(string $username, string $password, string $scope) : StdClass
    {
        $uri = 'http://' . USER_API_HOST . '/grant';
        $request = compact('username', 'password', 'scope');
        return $this->getRestResponse($uri, $request);
    }

    private function getValidateResponse(string $token, string $scope) : StdClass
    {
        $uri = 'http://' . USER_API_HOST . '/validate';
        $request = compact('token', 'scope');
        return $this->getRestResponse($uri, $request);
    }

    private function getRestResponse(string $uri, array $request) : StdClass
    {
        echo "Sending request to {$uri}:\n";
        echo json_encode($request, JSON_PRETTY_PRINT) . "\n";
        $response = Request::post($uri)
            ->expectsJson()
            ->sendsJson()
            ->body(json_encode($request))
            ->send();
        $body = $response->body;
        echo "Received response from {$uri}:\n";
        echo json_encode($body, JSON_PRETTY_PRINT) . "\n";
        return $body;
    }

}