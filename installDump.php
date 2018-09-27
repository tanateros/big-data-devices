<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";
$config = parse_ini_file(__DIR__ . '/config.ini');
$countTestData = 1000000;
ini_set('memory_limit', '4095M'); // 4 GBs minus 1 MB

$pdo = (new \High\Helper\Db(
    $config['host'],
    $config['db'],
    $config['user'],
    $config['pass']
))->getPdo();

$generator = new \High\Helper\GenerateDbDump($pdo);

for ($i = 0; $i < $countTestData; $i++) {
    $generator->generate();
}
