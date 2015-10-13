<?php

Phar::mapPhar('index.php');

defined('CONNECTOR_DIR') || define('CONNECTOR_DIR', __DIR__);

require_once 'phar://index.php/index.php';

__HALT_COMPILER();