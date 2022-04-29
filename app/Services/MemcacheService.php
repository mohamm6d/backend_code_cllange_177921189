<?php

namespace App\Services;

use App\Handlers\CacheHandler;
use Memcache;
use UnexpectedValueException;

class MemcacheService extends CacheHandler
{
    public function __construct()
    {
        $this->host = getenv('MEMCACHE_HOST');
        $this->port = getenv('MEMCACHE_PORT');
        if (empty($this->host) || empty($this->port)) {
            throw new UnexpectedValueException('Host and/or Port not set');
        }
        $this->cacheObject = new Memcache();
    }

    /**
     * Set a value in cache
     *
     * @param string $key
     * @param string $value
     * @param array<string> $options
     * @return void
     */
    public function set(string $key, string $value, array $options = []): void
    {
        $this->cacheObject->set($key, $value, $options['is_compressed'] ?? null, $options['ttl'] ?? 0);
    }
}
