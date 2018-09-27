<?php

namespace High\Client;

use High\Entity\DeviceData;
use High\Helper\{
    Http\Custom\JsonResponse, Http\Request, Http\Response
};
use Sinergi\BrowserDetector\{
    Device, Language, Os
};

/**
 * Class ClientHttpWithAgent
 *
 * @package High\Client
 */
class ClientHttpWithAgent extends ClientHttpVisitor
{
    const MOBILE = 'mobile';
    const DESKTOP = 'desktop';
    const DEFAULT_VERSION = 1; // TODO: this workaround because I haven't real data

    /**
     * @return $this
     */
    public function handle()
    {
        $language = (new Language())->getLanguage();
        $os = new Os();
        $osVersion = $os->getVersion();
        $deviceType = $os->isMobile()? (new Device())->getName() :self::DESKTOP;
        $appVersion = self::DEFAULT_VERSION;

        $ip = @$_SERVER['HTTP_CLIENT_IP'] ?: @$_SERVER['HTTP_X_FORWARDED_FOR'] ?: @$_SERVER['REMOTE_ADDR'];
        $ip = ip2long($ip);

        $request = new Request();
        $requestData = $request->get();

        $this->userRequestData = [
            'ip' => $ip,
            'lang' => $language,
            'deviceType' => $deviceType,
            'osVersion' => $osVersion,
            'appVersion' => $appVersion,
            'data' => json_encode($requestData),
        ];

        $deviceData = new DeviceData($this->db, $this->userRequestData);
        $this->responseData = $deviceData->getPrepareResponseDiffTime();
        /* @see here haven't data for caching */

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
