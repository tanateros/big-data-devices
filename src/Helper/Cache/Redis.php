<?php

namespace High\Helper\Cache;

use Predis\Client as RedisClient;

/**
 * Class Redis
 *
 * @package High\Helper\Cache
 */
class Redis
{
    /** @var RedisClient $redis */
    protected $redis;

    /**
     * Redis constructor.
     *
     * @param $scheme
     * @param $host
     * @param $port
     */
    public function __construct($scheme, $host, $port)
    {
        $connectData = ($host == '127.0.0.1' && $port == 6379)
            ? []
            : [$scheme, $host, $port];
        $this->redis = new RedisClient($connectData);
    }

    /**
     * @return bool
     */
    public function allow()
    {
        try {
            $this->redis->ping();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @return RedisClient
     */
    public function getCache()
    {
        return $this->redis;
    }
}
