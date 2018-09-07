<?php

namespace App\Model;

class Token
{
    private $access_token;
    private $mac_key;
    private $expires_in;
    private $scope;
    private $token_type = 'mac';

    public function __construct(string $access_token, string $mac_key, int $expires_in,
        string $scope)
    {
        $this->access_token = $access_token;
        $this->mac_key      = $mac_key;
        $this->expires_in   = $expires_in;
        $this->scope        = $scope;
    }

    public function toArray() : array
    {
        return [
            'access_token'  => $this->access_token,
            'mac_key'       => $this->mac_key,
            'expires_in'    => $this->expires_in,
            'scope'         => $this->scope,
            'token_type'    => $this->token_type,
        ];
    }
}