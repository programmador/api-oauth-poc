<?php

namespace App\Model;

class Token
{
    private $id;
    private $key;
    private $ttl;
    private $scope;
    private $uid;
    private $type = 'mac';

    public function __construct(string $id, ?int $uid, ?string $macKey, ?int $minutes, string $scope)
    {
        $this->id       = $id;
        $this->uid      = $uid;
        $this->key      = $macKey;
        $this->ttl      = $minutes;
        $this->scope    = $scope;
    }

    public function toGrantRepresentation() : array
    {
        return [
            'access_token'  => $this->id,
            'mac_key'       => $this->key,
            'expires_in'    => $this->ttl,
            'scope'         => $this->scope,
            'token_type'    => $this->type,
        ];
    }

    public function toValidateRepresentation() : array
    {
        return [
            'user_id'       => $this->uid,
            'mac_key'       => $this->key,
        ];
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getUid() : int
    {
        return $this->uid;
    }

    public function getMacKey() : string
    {
        return $this->key;
    }
}