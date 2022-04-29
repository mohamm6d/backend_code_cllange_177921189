<?php

namespace App\Interfaces;

interface Controller {
    /**
     * Call other methods for the cache object
     *
     * @param string $name
     * @param array <string> $args
     */
    function __call(string $name, array $args): ?string;
}
