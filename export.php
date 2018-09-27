<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

ini_set('memory_limit', '4095M'); // 4 GBs minus 1 MB

require_once __DIR__ . "/vendor/autoload.php";
$config = parse_ini_file(__DIR__ . '/config.ini');
$config['logPath'] = __DIR__ . '/data/logs/';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$report = new \High\Client\ClientReport($config);

$filename = 'devices.json';

if (!file_exists($filename)) {
    $devices = json_encode($report->getExportReport());
    $fp = fopen($filename, 'w+');
    fwrite($fp, $devices);
    fclose($fp);
} else {
    $datetime = new \DateTime("NOW");
    $fileDateTime = (new \DateTime())->setTimestamp(filemtime($filename));
    $interval = $datetime->diff($fileDateTime);
    $diffTime = $interval->format("%Y-%M-%D %H:%I:%S");
    $diffDays = (int)$interval->format('%a');

    if ($diffDays) { // caching file for 1 day
        $devices = json_encode($report->getExportReport());
        $fp = fopen($filename, 'w+');
        fwrite($fp, $devices);
        fclose($fp);
    } else {
        $devices = file_get_contents($filename);
    }
}

header('Content-Type: application/json');
echo $devices;
