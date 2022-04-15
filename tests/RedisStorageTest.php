<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\ICanBoogie\Storage;

use ICanBoogie\Storage\RedisStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

class RedisStorageTest extends TestCase
{
	use TestStorageTrait;

	private Storage $storage;

	protected function setUp(): void
	{
		if (!class_exists('Redis'))
		{
			$this->markTestSkipped('The Redis extension is not available.');
		}

		if (!method_exists('Redis', 'scan'))
		{
			$this->markTestSkipped('Redis::scan() is not defined.');
		}

		$redis = new \Redis;
		$redis->connect(getenv('REDIS_HOST') ?: '127.0.0.1', 6379);
		$version = $redis->info()['redis_version'];

		if (version_compare($version, '2.8', '<'))
		{
			$this->markTestSkipped('The Redis v2.8 or later is required.');
		}

		$this->storage = new RedisStorage($redis, 'prefix_' . substr(sha1(uniqid()), 0, 8) . ':');
	}
}
