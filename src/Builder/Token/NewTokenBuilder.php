<?php

namespace App\Builder\Token;

use App\Model\Token;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;

class NewTokenBuilder implements Builder
{

    private $tokenId;
    private $uid;
    private $accessToken;
    private $macKey;
    private $scope;

    private $expiresIn;
    private $redis;

    public function __construct(Redis $redis)
    {
        $this->redis = $redis;
    }

    public function setTokenId(string $id)
    {
        $this->tokenId = $id;
    }

    public function setUid(int $uid)
    {
        $this->uid = $uid;
    }

    public function setKey(string $key)
    {
        $this->macKey = $key;
    }

    public function setScope(string $scope)
    {
        $this->scope = $scope;
    }

    public function setTtl(int $minutes)
    {
        $this->expiresIn = $minutes;
    }

    public function getResult() : Token
    {
        $this->syncRedis();
        return $this->getTokenObject();
    }

    private function syncRedis()
    {
        $this->syncPart($this->tokenId, $this->scope, Builder::KEY_PART_UID, $this->uid);
        $this->syncPart($this->tokenId, $this->scope, Builder::KEY_PART_MAC, $this->macKey);
    }

    private function syncPart(string $tokenId, string $scope, string $key, string $value)
    {
        $key = implode(':', [Builder::KEY_START, $scope, $tokenId, $key]);
        $this->redis->set($key, $value);
        $this->redis->expire($key, $this->getExpireSeconds());
    }

    private function getExpireSeconds() : int
    {
        return $this->expiresIn * 60;
    }

    private function getTokenObject() : Token
    {
        return new Token(
            $this->tokenId,
            $this->uid,
            $this->macKey,
            $this->expiresIn,
            $this->scope
        );
    }

}