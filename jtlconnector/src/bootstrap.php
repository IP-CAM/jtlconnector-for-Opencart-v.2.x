<?php

$loader = require_once(__DIR__ . '/../vendor/autoload.php');
$loader->add('', CONNECTOR_DIR . '/plugins');

use jtl\Connector\Application\Application;
use jtl\Connector\OpenCart\Connector;

$connector = Connector::getInstance();
$application = Application::getInstance();
$application->register($connector);
$application->run();