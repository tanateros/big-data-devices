<?php

require_once __DIR__ . "/vendor/autoload.php";
$curl = new \High\Helper\CustomProvider();

echo $curl->get('visit');
