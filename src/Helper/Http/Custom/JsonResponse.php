<?php

namespace High\Helper\Http\Custom;

use High\Helper\Http\Response;

/**
 * Class JsonResponse
 *
 * @package High\Helper\Http\Custom
 */
class JsonResponse extends Response
{
    /**
     * @return string
     */
    public function prepareResponse(): string
    {
        return json_encode($this->data);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->prepareResponse();
    }
}
