<?php

namespace App\Services;

use App\Handlers\CacheHandler;
use Redis;
use UnexpectedValueException;

class RedisService extends CacheHandler
{
    public function __construct()
    {
        $this->host = getenv('REDIS_HOST');
        $this->port = getenv('REDIS_PORT');        
        if (empty($this->host) || empty($this->port)) {
            throw new UnexpectedValueException('Host and/or Port not set');
        }
        $this->cacheObject = new Redis();
    }

    /**
     * Append a value to the list
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    public function lPush(string $key, string $value): void
    {
        $this->cacheObject->lPush($key, $value);
    }
}
