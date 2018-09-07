<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use SymfonyBundles\RedisBundle\Redis\ClientInterface as Redis;
use Symfony\Component\DependencyInjection\ContainerInterface;

class TokenStorage
{
    private $container;
    private $expires;
    private $logger;
    private $redis;

    public function __construct(LoggerInterface $logger, Redis $redis,
        ContainerInterface $container)
    {
        $this->container = $container;
        $this->expires = $this->container->getParameter('tokens')['expires_in'];
        $this->logger = $logger;
        $this->redis = $redis;
    }

    public function getToken()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];
        $index = array_rand($messages);
        $this->redis->set('library', $this->expires);
        $this->logger->info("hello from service " . $messages[$index]);
    }
}