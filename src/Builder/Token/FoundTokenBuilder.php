<?php

namespace App\Builder\Token;

use App\Model\Token;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;

class FoundTokenBuilder implements Builder
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
        $this->fetchKeyFromRedis();
        $this->fetchUidFromRedis();
        return $this->getTokenObject();
    }

    private function fetchKeyFromRedis()
    {
        $this->macKey = $this->getPart($this->tokenId, $this->scope, Builder::KEY_PART_MAC);
    }

    private function fetchUidFromRedis()
    {
        $this->uid = $this->getPart($this->tokenId, $this->scope, Builder::KEY_PART_UID);
    }

    private function getPart(string $tokenId, string $scope, string $key)
    {
        $key = implode(':', [Builder::KEY_START, $scope, $tokenId, $key]);
        return $this->redis->get($key);
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