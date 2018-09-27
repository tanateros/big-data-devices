<?php

namespace High\Entity;

/**
 * Class Report
 *
 * @package High\Entity
 */
class Report extends AbstractEntity
{
    const LIMIT = 5;

    /**
     * @param int|null $page
     * @param bool     $export
     *
     * @return array
     */
    public function getAllDevicesWithPagination(int $page = null, $export = false): array
    {
        $page--; // for zero page
        $sql = "SELECT f.fingerprint, d.id as device_id, INET_NTOA(di.ip) as ip
                FROM fingerprints AS f
                INNER JOIN devices AS d ON f.id = d.fingerprint_id
                INNER JOIN devices_ips AS di ON d.id = di.device_id";

        if (!$export && $page !== false) {
            $sql .= " LIMIT " .  $page * self::LIMIT . ', ' . self::LIMIT;
        }

        return $this->pdo
            ->query($sql)
            ->fetchAll();
    }

    public function getCountDevices()
    {
        return $this->pdo->query("SELECT COUNT(id) FROM devices")->fetch()[0];
    }

    public function getDeviceDetails(int $deviceId, string $ip)
    {
        $sql = "SELECT *, f.fingerprint, d.id as device_id, INET_NTOA(di.ip) as ip
                FROM fingerprints AS f
                INNER JOIN devices AS d ON f.id = d.fingerprint_id
                INNER JOIN devices_ips AS di ON d.id = di.device_id
                WHERE di.device_id = $deviceId AND di.ip = INET_ATON('{$ip}')";

        return $this->pdo
            ->query($sql)
            ->fetch(\PDO::FETCH_ASSOC);
    }
}
