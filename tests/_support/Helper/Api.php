<?php
namespace App\Tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Api extends \Codeception\Module
{
    public function dumpResponse()
    {
        $response = $this->getModule('REST')->response;
        $logger = $this->getSymfonyService("monolog.logger");
        $logger->error($response);
    }

    /**
     * Codeception + Symfony 4.1 bug.
     * Damn, I've spent so much time to find these issues with a solution!
     * @see https://github.com/Codeception/Codeception/issues/4976
     * @see https://github.com/Codeception/Codeception/issues/5073
     */
    private function getSymfonyService(string $id)
    {
        return $this->getModule('Symfony')->grabService('test.service_container')->get($id);
    }
}
