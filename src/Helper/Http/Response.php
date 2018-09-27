<?php

namespace High\Helper\Http;

/**
 * Class Response
 *
 * @package High\Helper\Http
 */
class Response
{
    protected $data;

    /**
     * Response constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->data;
    }
}
