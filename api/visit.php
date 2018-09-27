<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";
$config = parse_ini_file(__DIR__ . '/../config.ini');
$config['logPath'] = __DIR__ . '/../data/logs/';

try {
    //$client = new \High\Client\ClientHttpVisitor($config);
    //$client = new \High\Client\ClientHttpWithAgent($config);
    $client = new \High\Client\ClientHttpWithAgentAndLogger($config);
    echo $client->handle()->send();
} catch (\Exception $e) {
    $log = new \Monolog\Logger('Errors');
    $log->pushHandler(
        new \Monolog\Handler\StreamHandler(
            $config['logPath'] . 'error.log',
            \Monolog\Logger::DEBUG
        )
    );
    $log->error($e->getMessage());
    echo $e->getMessage();
}
