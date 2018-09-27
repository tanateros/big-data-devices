<?php

namespace High\Client;

use High\Helper\{
    Cache\Redis, Db, Http\Custom\JsonResponse, Http\Request, Http\Response
};

/**
 * Class ClientHttpVisitor
 *
 * @package High\Client
 */
class ClientHttpVisitor
{
    /** @var array $config */
    protected $config;

    /** @var array $request */
    protected $request;

    /** @var array $userRequestData */
    protected $userRequestData;

    /** @var Db $db */
    protected $db;

    /** @var Redis|null $cache */
    protected $cache = null;

    /** @var mixed|null $responseData */
    protected $responseData = null;

    /**
     * ClientHttpVisitor constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
        $this->db = new Db(
            $config['host'],
            $config['db'],
            $config['user'],
            $config['pass']
        );
        $redis = new Redis(
            $config['cache_scheme'],
            $config['cache_host'],
            $config['cache_port']
        );

        if ($redis->allow()) {
            $this->cache = $redis;
        }
    }

    /**
     * @return $this
     */
    public function handle()
    {
        $request = new Request();
        $requestData = $request->get();

        $key = base64_encode(json_encode($requestData));

        if ($this->cache) {
            $cache = $this->cache->getCache();

            if ($cache->exists($key)) {
                $this->responseData = $cache->get($key);
            } else {
                $prepareData = json_encode($requestData);
                $cache->set($key, $prepareData);
                $this->responseData = $prepareData;
            }
        } else {
            $this->responseData = $requestData;
        }

        return $this;
    }

    /**
     * @return Response
     */
    public function send(): Response
    {
        return new JsonResponse($this->responseData);
    }
}
