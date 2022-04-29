<?php

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use App\Controllers\CacheController;
use App\Services\MemcacheService;
use App\Services\RedisService;
use App\Controllers\DotEnvController;

(new DotEnvController(__DIR__ . '/.env'))->load();

if (getenv('APP_ENV') === 'local') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

try {
    $redisController = new CacheController(new RedisService());
    $redisController->connect();
    $redisController->set('one', '1');
    $redisController->lpush('two', '1');
    $redisController->lpush('two', '2');
    echo $redisController->get('one');
} catch (Exception $th) {
    error_log($th->getMessage());
}

try {
    $memcacheController = new CacheController(new MemcacheService());
    $memcacheController->connect();
    $memcacheController->set('one', '1');
    $memcacheController->set('two', '2');
    $memcacheController->lpush('two', '2');
    echo $memcacheController->get('one');
    echo $memcacheController->get('two');
} catch (BadMethodCallException $th) {
    error_log($th->getMessage());
} catch (Exception $th) {
    error_log($th->getMessage());
}
