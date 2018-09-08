<?php

namespace App\Builder\Token;

use App\Model\Token;

interface Builder
{
    const KEY_START     = 'grant';
    const KEY_PART_UID  = 'uid';
    const KEY_PART_MAC  = 'key';

    public function setTokenId(string $id);
    public function setUid(int $uid);
    public function setKey(string $key);
    public function setScope(string $scope);
    public function setTtl(int $minutes);

    public function getResult() : Token;
}
