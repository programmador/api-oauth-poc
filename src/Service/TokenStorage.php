<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class TokenStorage
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function getToken()
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];
        $index = array_rand($messages);
        $this->logger->info("hello from service " . $messages[$index]);
    }
}