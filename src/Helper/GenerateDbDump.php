<?php

namespace High\Helper;
use High\Client\ClientHttpWithAgent;
use High\Entity\Fingerprint;

/**
 * Class GenerateDbDump
 *
 * @package High\Helper
 */
class GenerateDbDump
{
    /** @var \PDO $pdo */
    protected $pdo;

    /**
     * GenerateDbDump constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function generate()
    {
        $currentTime = date("Y-m-d H:i:s");
        $this->pdo->beginTransaction();

        $stm = $this->pdo->prepare(
            'INSERT INTO fingerprints (fingerprint) VALUES (:fingerprint)'
        );
        $stm->execute([
            'fingerprint' => md5(microtime(true)),
        ]);
        $fingerprintId = $this->pdo->lastInsertId();

        $stmDevices = $this->pdo->prepare(
            'INSERT INTO devices (uuid, fingerprint_id, device_type, os, os_version, lang, data, app_version, created_at)
            VALUES (:uuid, :fingerprint_id, :device_type, :os, :os_version, :lang, :data, :app_version, :created_at)'
        );
        $stmDevices->execute([
            'uuid' => Uuid::generate(),
            'fingerprint_id' => $fingerprintId,
            'device_type' => array_rand([ClientHttpWithAgent::DESKTOP, ClientHttpWithAgent::MOBILE]),
            'os' => array_rand(['Linux', 'Unix', 'MacOS', 'Windows', 'Android', 'iOS']),
            'os_version' => rand(1, 100),
            'lang' => array_rand(['ru', 'en', 'it', 'jp', 'ch']),
            'data' => '{}',
            'app_version' => ClientHttpWithAgent::DEFAULT_VERSION,
            'created_at' => $currentTime,
        ]);

        $stmDevicesIps = $this->pdo->prepare(
            'INSERT INTO devices_ips (device_id, ip, data, created_at, last_update_at, prev_last_calls_time)
            VALUES (:device_id, :ip, :data, :created_at, :last_update_at, :prev_last_calls_time)'
        );
        $stmDevicesIps->execute([
            'device_id' => $this->pdo->lastInsertId(),
            'ip' => ip2long(rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255)),
            'data' => '{}',
            'created_at' => $currentTime,
            'last_update_at' => $currentTime,
            'prev_last_calls_time' => $currentTime,
        ]);
        $this->pdo->commit();
    }
}
