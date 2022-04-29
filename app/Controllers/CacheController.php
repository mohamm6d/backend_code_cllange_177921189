<?php

namespace App\Controllers;

use App\Handlers\CacheHandler;
use App\Interfaces\Controller;
use BadMethodCallException;

class CacheController implements Controller
{
    private CacheHandler $cacheService;

    public function __construct(CacheHandler $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Call other methods for the cache object
     *
     * @param string $name
     * @param array <string> $args
     */
    public function __call(string $name, array $args): ?string
    {
        if (!method_exists($this->cacheService, $name)) {
            throw new BadMethodCallException("Invalid method call " . $name);
        }

        return call_user_func_array(
            [
				$this->cacheService,
				$name
			],
            $args
        );
    }
}
