<?php

namespace High\Client;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class ClientHttpWithAgentAndLogger
 *
 * @package High\Client
 */
class ClientHttpWithAgentAndLogger extends ClientHttpWithAgent
{
    /** @var Logger $log */
    protected $log;

    /**
     * ClientHttpWithAgentAndLogger constructor.
     *
     * @param array $config
     *
     * @throws \Exception
     */
    public function __construct(array $config)
    {
        parent::__construct($config);

        $this->log = new Logger('Information');

        $this->log->pushHandler(
            new StreamHandler($config['logPath'] . 'info.log', Logger::INFO)
        );
    }

    /**
     * @return $this
     */
    public function handle()
    {
        parent::handle();

        $request = json_encode($this->userRequestData);
        $response = json_encode($this->responseData);
        $this->log->info("User request: {$request}. Response: {$response}.");

        return $this;
    }
}
