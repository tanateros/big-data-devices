<?php

namespace High\Helper;

/**
 * Class CustomProvider
 *
 * @package High\Helper
 */
class CustomProvider
{
    /** @var resource $curl */
    protected $curl;

    /**
     * CustomProvider constructor.
     */
    public function __construct()
    {
        $this->curl = curl_init();
    }

    public function __destruct()
    {
        curl_close($this->curl);
    }

    /**
     * @param string $file
     * @param array  $params
     *
     * @return mixed
     */
    public function get(string $file = 'visit', array $params = [])
    {
        curl_setopt(
            $this->curl,
            CURLOPT_URL,
            "http://{$_SERVER['SERVER_NAME']}/api/{$file}.php"
            . '?' . http_build_query($params)
        );
        curl_setopt($this->curl, CURLOPT_RETURNTRANSFER,true);

        return curl_exec($this->curl);
    }
}
