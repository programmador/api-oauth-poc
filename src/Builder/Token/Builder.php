<?php

namespace App\Builder\Token;

use App\Model\Token;

interface Builder
{
    public function setTokenId(string $id);
    public function setUid(int $uid);
    public function setKey(string $key);
    public function setScope(string $scope);
    public function setTtl(int $minutes);

    public function getResult() : Token;
}
