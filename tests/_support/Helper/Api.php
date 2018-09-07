<?php

namespace App\Tests\Helper;

use Codeception\Module;
use Psr\Log\LoggerInterface as Logger;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends Module
{
    public function dumpResponse()
    {
        $response = $this->getModule('REST')->response;
        $this->getLogger()->info($response);
    }

    public function getUserFixtures() : array
    {
        $config = $this->getContainer()->getParameter('datafixtures');
        $users = $config['users'];
        return $users;
    }

    public function getLogger() : Logger
    {
        return $this->getContainer()->get("monolog.logger");
    }

    /**
     * Codeception + Symfony 4.1 bug.
     * @see https://github.com/Codeception/Codeception/issues/4976
     * @see https://github.com/Codeception/Codeception/issues/5073
     */
    private function getContainer() : Container
    {
        return $this->getModule('Symfony')->grabService('test.service_container');
    }
}
