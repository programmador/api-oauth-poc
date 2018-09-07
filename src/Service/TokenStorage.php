<?php

namespace App\Service;

use App\Model\Token;
use Psr\Log\LoggerInterface;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TokenStorage
{
    private const KEY_LENGTH    = 16;
    private const TOKEN_LENGTH  = 16;

    private $container;
    private $expires;
    private $logger;
    private $redis;

    public function __construct(LoggerInterface $logger, Redis $redis,
        ContainerInterface $container)
    {
        $this->container    = $container;
        $this->expires      = $this->container->getParameter('tokens')['expires_in'];
        $this->logger       = $logger;
        $this->redis        = $redis;
    }

    public function getToken()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];
        $index = array_rand($messages);
        $this->redis->set('tokens:videos:uid', $this->expires);
        $this->logger->info("hello from service " . $messages[$index]);
    }

    public function createToken(int $uid, string $scope) : Token
    {
        $id = bin2hex(random_bytes(self::TOKEN_LENGTH));
        $mac = bin2hex(random_bytes(self::KEY_LENGTH));
        $token = new Token($id, $mac, $this->expires, $scope);
        return $token;
    }
}