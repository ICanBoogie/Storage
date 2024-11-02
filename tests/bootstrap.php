<?php

use ICanBoogie\Storage\APCStorage;

define('ICanBoogie\Storage\SANDBOX_DIR', __DIR__ . '/sandbox');

// https://www.php.net/manual/en/apcu.configuration.php#ini.apcu.use-request-time
ini_set('apc.use_request_time', false);

require __DIR__ . '/../vendor/autoload.php';
require 'TestStorageTrait.php';

APCStorage::is_available() or die("APC is not available");
