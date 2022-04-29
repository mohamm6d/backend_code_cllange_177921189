<?php

namespace App\Handlers;

abstract class CacheHandler
{
    protected object $cacheObject;
    protected string $host;
    protected string $port;
    
    public function connect(): void
    {
        try {
            $this->cacheObject->connect($this->host, (int)($this->port));
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
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
        $this->cacheObject->set($key, $value, $options['ttl'] ?? null);
    }

    /**
     * Get a cached value
     * 
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->cacheObject->get($key);
    }
}
