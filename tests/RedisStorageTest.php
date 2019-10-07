<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage;

use PHPUnit\Framework\TestCase;

class RedisStorageTest extends TestCase
{
	use TestStorageTrait;

	/**
	 * @var Storage
	 */
	private $storage;

	public function setUp()
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

	public function test_default_ttl()
	{
		$test_ttl   = 1000;
		$redis = $this->createMock(\Redis::class);

		$redis->expects($this->once())
			->method('set')
			->with($this->anything(), $this->anything(), $test_ttl);

		$storage_with_ttl = new RedisStorage($redis, 'prefix_', $test_ttl);
		$storage_with_ttl->store('key', 'value');
	}

	public function test_default_ttl_override()
	{
		$test_ttl            = 1000;
		$test_ttl_override   = 2000;
		$redis = $this->createMock(\Redis::class);

		$redis->expects($this->once())
			->method('set')
			->with($this->anything(), $this->anything(), $test_ttl_override);

		$storage_with_ttl = new RedisStorage($redis, 'prefix_', $test_ttl);
		$storage_with_ttl->store('key', 'value', $test_ttl_override);
	}

	public function test_no_default_ttl()
	{
		$redis = $this->createMock(\Redis::class);

		$redis->expects($this->once())
			->method('set')
			->with($this->anything(), $this->anything(), null);

		$storage_with_ttl = new RedisStorage($redis, 'prefix_');
		$storage_with_ttl->store('key', 'value');
	}
}
