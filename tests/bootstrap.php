<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

define('ICanBoogie\Storage\SANDBOX_DIR', __DIR__ . '/sandbox');

// https://www.php.net/manual/en/apcu.configuration.php#ini.apcu.use-request-time
ini_set('apc.use_request_time', false);

require __DIR__ . '/../vendor/autoload.php';
require 'TestStorageTrait.php';
