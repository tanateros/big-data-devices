<?php

namespace High\Client;

use High\Entity\DeviceMessage;
use High\Entity\Report;

/**
 * Class ClientReport
 *
 * @package High\Client
 */
class ClientReport extends ClientHttpVisitor
{
    /**
     * @param int|null $page
     *
     * @return array
     */
    public function getReportData(int $page = null): array
    {
        $report = new Report($this->db);
        // TODO: If you rarely add new devices then it's better to use page caching
        $devices = $report->getAllDevicesWithPagination($page);

        return [
            'devices' => $devices,
            'count' => $report->getCountDevices(),
        ];
    }

    /**
     * @return array
     */
    public function getExportReport(): array
    {
        return (new Report($this->db))->getAllDevicesWithPagination(null, true);
    }

    public function getDeviceDetails(int $deviceId, string $ip)
    {
        return (new Report($this->db))->getDeviceDetails($deviceId, $ip);
    }

    public function pushMessage(int $deviceId, string $message)
    {
        return (new DeviceMessage($this->db))->create($deviceId, $message);
    }
}
