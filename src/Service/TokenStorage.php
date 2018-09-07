<?php

namespace App\Service;

use App\Builder\Token\Director;
use App\Builder\Token\TokenBuilder as Builder;
use App\Model\Token;
use Psr\Log\LoggerInterface as Logger;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class TokenStorage
{
    private $expires;
    private $logger;
    private $redis;

    public function __construct(Logger $logger, Redis $redis, Container $container)
    {
        $this->expires = $container->getParameter('tokens')['expires_in'];
        $this->logger = $logger;
        $this->redis = $redis;
    }

    public function getToken(int $uid, string $scope) : Token
    {
        $builder = new Builder($this->redis);
        $director = new Director($builder);
        $director->constructToken($uid, $scope, $this->expires);
        $token = $builder->getResult();
        $this->logTokenGeneration($token);
        return $token;
    }

    /**
     * I'll leave logging here just for self-education purpose.
     * It's not a production project.
     * Never leave such a garbage for production!
     * At least implement smth like if($debug)
     */
    private function logTokenGeneration(Token $token)
    {
        $this->logger->info('generated new token ' . $token->getId());
    }

}