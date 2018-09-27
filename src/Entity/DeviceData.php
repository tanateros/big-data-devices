<?php

namespace High\Entity;

use High\Helper\Uuid;
use Sinergi\BrowserDetector\Os;

/**
 * Class DeviceData
 *
 * @package High\Entity
 */
class DeviceData extends AbstractEntity
{
    public $ip;

    public $lang;

    public $deviceType;

    public $osVersion;

    public $appVersion;

    public $data;

    public $fingerprintId;

    protected $lastCallTime;

    protected $prevCallTime;

    protected $diffCallTimes;

    /**
     * DeviceData constructor.
     *
     * @param       $db
     * @param array $data
     */
    public function __construct($db, array $data)
    {
        parent::__construct($db);

        $this->prepareValidate($data);

        $dataFp = [
//            $this->ip,
            $this->lang,
            $this->deviceType,
            $this->osVersion,
            $this->appVersion
        ];
        $fingerprintEntity = new Fingerprint($db);
        $fingerprint = $fingerprintEntity->generateFingerprint($dataFp);

        if ($this->fingerprintId = $fingerprintEntity->getIdByFingerprint($fingerprint)) {
            // update for deviceData timers
            $this->prepareTimeDiffData();
        } else {
            // create deviceData and fp
            $this->create($fingerprintEntity, $fingerprint);
        }
    }

    /**
     * @param Fingerprint $fingerprintEntity
     * @param string      $fingerprint
     *
     * @return $this
     */
    public function create(Fingerprint $fingerprintEntity, string $fingerprint)
    {
        $this->pdo->beginTransaction();

        $currentTime = date("Y-m-d H:i:s");
        $this->fingerprintId = $fingerprintEntity->create([
            'fingerprint' => $fingerprint
        ], $this->pdo);

        $stmDevices = $this->pdo->prepare(
            'INSERT INTO devices (uuid, fingerprint_id, device_type, os, os_version, lang, data, app_version, created_at)
            VALUES (:uuid, :fingerprint_id, :device_type, :os, :os_version, :lang, :data, :app_version, :created_at)'
        );
        $stmDevices->execute([
            'uuid' => Uuid::generate(),
            'fingerprint_id' => $this->fingerprintId,
            'device_type' => $this->deviceType,
            'os' => (new Os())->getName(),
            'os_version' => $this->osVersion,
            'lang' => $this->lang,
            'data' => $this->data,
            'app_version' => $this->appVersion,
            'created_at' => $currentTime,
        ]);

        $stmDevicesIps = $this->pdo->prepare(
            'INSERT INTO devices_ips (device_id, ip, data, created_at, last_update_at, prev_last_calls_time)
            VALUES (:device_id, :ip, :data, :created_at, :last_update_at, :prev_last_calls_time)'
        );
        $stmDevicesIps->execute([
            'device_id' => $this->pdo->lastInsertId(),
            'ip' => $this->ip,
            'data' => $this->data,
            'created_at' => $currentTime,
            'last_update_at' => $currentTime,
            'prev_last_calls_time' => $currentTime,
        ]);
        $this->pdo->commit();

        $this->lastCallTime = $currentTime;
        $this->prevCallTime = $currentTime;

        return $this;
    }

    /**
     * @param array $data
     *
     * @return $this
     */
    public function prepareValidate(array $data)
    {
        $args = array(
            'ip'=> array(
                'filter'    => FILTER_VALIDATE_INT,
                'flags'     => FILTER_REQUIRE_SCALAR,
            ),
            'lang'=> array(
                'filter'    => FILTER_SANITIZE_STRING,
                'options'   => array('min_range' => 2, 'max_range' => 2)
            ),
            'deviceType'=> array(
                'filter'    => FILTER_SANITIZE_STRING,
            ),
            'osVersion'=> array(
                'filter'    => FILTER_SANITIZE_STRING,
            ),
            'appVersion'=> array(
                'filter'    => FILTER_SANITIZE_STRING,
            ),
            'data'=> array(
                'filter'    => FILTER_SANITIZE_STRING,
            ),
        );

        $data = filter_var_array($data, $args);

        $this->ip = $data['ip'];
        $this->lang = $data['lang'];
        $this->deviceType = $data['deviceType'];
        $this->osVersion = $data['osVersion'];
        $this->appVersion = $data['appVersion'];
        $this->data = $data['data'];

        return $this;
    }

    /**
     * @return array
     */
    public function getPrepareResponseDiffTime(): array
    {
        if (!$this->diffCallTimes) {
            $this->setDiffCallTimes();
        }

        return [
            'last_call_time' => $this->lastCallTime,
            'prev_call_time' => $this->prevCallTime,
            'diff_call_times' => $this->diffCallTimes,
            'ip' => $this->ip,
        ];
    }

    /**
     * @return $this
     */
    public function prepareTimeDiffData()
    {
        $deviceId = $this->pdo
            ->query("SELECT id FROM devices WHERE fingerprint_id = {$this->fingerprintId}")
            ->fetchColumn();
        $currentTime = date("Y-m-d H:i:s");
        $this->pdo->beginTransaction();
        $this->pdo
            ->query(
                "UPDATE devices_ips
                SET prev_last_calls_time = last_update_at, last_update_at = '{$currentTime}'
                WHERE device_id = {$deviceId} AND ip = {$this->ip}"
            );
        $this->lastCallTime = $currentTime;
        $this->prevCallTime = $this->pdo
            ->query("SELECT prev_last_calls_time FROM devices_ips WHERE device_id = {$deviceId} AND ip = {$this->ip}")
            ->fetch()['prev_last_calls_time'];
        $this->setDiffCallTimes();
        $this->pdo->commit();

        return $this;
    }

    /**
     * @return $this
     */
    protected function setDiffCallTimes()
    {
        $datetime1 = new \DateTime($this->lastCallTime);
        $datetime2 = new \DateTime($this->prevCallTime);
        $interval = $datetime1->diff($datetime2);
        $this->diffCallTimes = $interval->format("%Y-%M-%D %H:%I:%S");

        return $this;
    }
}
