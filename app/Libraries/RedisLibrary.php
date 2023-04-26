<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;

class RedisLibrary
{
    /**
     * Retrieve data in redis
     */
    public function get(string $sKey)
    {
        return Redis::get($sKey);
    }

    /**
     * Set Data in redis
     */
    public function setData(string $sKey, string $sData)
    {
        return Redis::set($sKey, $sData);
    }

    /**
     * Remove key in redis
     */
    public function removeKey(string $sKey)
    {
        return Redis::del($sKey);
    }
}