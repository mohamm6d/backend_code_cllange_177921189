<?php
/**
 * Created by PhpStorm.
 * User: isnain
 * Date: 09.08.21
 * Time: 10:14
 */

interface CacheManagerInterface{
    public function connect(string $host,string $port);
    public function set(string $key, string $value, string $is_compressed = null, string $ttl = null);
    public function get(string $key): string;
    public function lpush(string $key, string $value);        
}

class RedisCacheConnector {
    private $reids;

    public function __construct() {
        $this->reids = new \Redis();
    }

    public function connect(string $host,string $port) {
        $this->reids->connect($host,$port);
    }

    public function lpush(string $key, string $value) {
        $this->reids->lPush($key,$value); 
    }

}

class MemCacheConnector {
    private $memcache;

    public function __construct() {
        $this->memcache = new \Memcache();
    }

    public function connect(string $host,string $port): void {
        $this->memcache->connect($host,$port);
    }

    public function lpush(string $key, string $value) {
        throw new \Exception("method not supported");
    }
}

class CacheConnectionFactory {
    private const REDIS = 'redis';
    private const MEMCACHED = 'memcached';

    public static function connect(string $driverName = "redis") {
        if (strtolower($driverName) === self::REDIS) {
            return new RedisConnector();
        } else if (strtolower($driverName) === self::MEMCACHED) {
            return new MemCacheConnector();
        } else {
            throw new Exception('Invalid Driver');
        }

        return null;
    }
}

class CacheManager implements CacheManagerInterface{

    private $cacheConnector;

    public function __construct($cacheConnector) {
        $this->cacheConnector = $cacheConnector;

        // or you can use connection factory
        $connection = 'redis'; // this variable should be passed to construct
        $this->cacheConnector = CacheConnectionFactory::connect($connection);
    }

    public function connect(string $host,string $port): self   {
        $this->cacheConnector->connect($host,$port);

        return $this;
    }    

    public function set(string $key, string $value, string $is_compressed = null, string $ttl = null): self  {
        $this->cacheConnector->set($key, $value, $is_compressed, $ttl);

        return $this;
    }

    public function get(string $key): string {
        return $this->cacheConnector->get($key);
    }

    public function lpush(string $key, string $value): self {
        $this->cacheConnector->lpush($key, $value);
        return $this;
    }
}

// if you want to use connection factory then following line
$redisCache2 = new CacheManager('redis');

$redisCache = new CacheManager(new RedisCacheConnector());
$redisCache->connect('somehost','121')
    ->set('one','1')
    ->lpush('two','1')
    ->lpush('two','2');

echo $redisCache->get('one');

$memCache = new CacheManager(new MemCacheConnector());
$memCache->connect('somehost','121')
    ->set('one','1')
    ->lpush('two','2'); // generates exception

echo $memCache->get('one');
?>
                            