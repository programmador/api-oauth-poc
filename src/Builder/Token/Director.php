<?php

namespace App\Builder\Token;

class Director
{
    private const KEY_LENGTH        = 16;
    private const TOKEN_LENGTH      = 16;

    private $builder;

    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function constructToken(int $uid, string $scope, int $ttl) : self
    {
        $this->builder->setTokenId($this->getAccessToken());
        $this->builder->setUid($uid);
        $this->builder->setKey($this->getMacKey());
        $this->builder->setScope($scope);
        $this->builder->setTtl($ttl);
        return $this;
    }

    private function getMacKey()
    {
        return bin2hex(random_bytes(self::KEY_LENGTH));
    }

    private function getAccessToken()
    {
        return bin2hex(random_bytes(self::TOKEN_LENGTH));
    }

}
