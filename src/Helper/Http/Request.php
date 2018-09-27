<?php

namespace High\Helper\Http;

/**
 * Class Request
 *
 * @package High\Helper\Http
 */
class Request
{
    /**
     * @param string|null $param
     *
     * @return mixed
     */
    public function get(string $param = null)
    {
        return $param ? $_GET[$param] : $_GET;
    }
}
