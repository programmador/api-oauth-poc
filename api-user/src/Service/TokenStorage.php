<?php

namespace App\Service;

use App\Builder\Token\Director;
use App\Builder\Token\FoundTokenBuilder;
use App\Builder\Token\NewTokenBuilder;
use App\Model\Token;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TokenStorage
{
    private $expires;
    private $redis;

    public function __construct(Redis $redis, Container $container)
    {
        $this->expires = $container->getParameter('tokens')['expires_in'];
        $this->redis = $redis;
    }

    public function newToken(int $uid, string $scope) : Token
    {
        $builder = new NewTokenBuilder($this->redis);
        $director = new Director($builder);
        $director->constructNewToken($uid, $scope, $this->expires);
        return $builder->getResult();
    }

    public function findToken(string $tokenId, string $scope) : Token
    {
        $builder = new FoundTokenBuilder($this->redis);
        $director = new Director($builder);
        $director->constructFoundToken($tokenId, $scope);
        return $builder->getResult();
    }

}