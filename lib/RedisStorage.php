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

/**
 * A storage using Redis.
 */
class RedisStorage implements Storage, \ArrayAccess
{
	use Storage\ArrayAccess;
	use Storage\ClearWithIterator;

	/**
	 * @var \Redis
	 */
	private $redis;

	/**
	 * @var string
	 */
	private $prefix;

	/**
	 * @param \Redis|mixed $redis
	 */
	public function __construct($redis, string $prefix)
	{
		$this->redis = $redis;
		$this->prefix = $prefix;
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve(string $key)
	{
		$value = $this->redis->get($this->prefix . $key);

		if ($value === false)
		{
			return null;
		}

		return unserialize($value);
	}

	/**
	 * @inheritdoc
	 */
	public function exists(string $key): bool
	{
		return (bool) $this->redis->exists($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function store(string $key, $value, int $ttl = null): void
	{
		$key = $this->prefix . $key;

		if ($ttl)
		{
			$this->redis->set($key, serialize($value), $ttl);

			return;
		}

		$this->redis->set($key, serialize($value));
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate(string $key): void
	{
		$this->redis->del($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator(): iterable
	{
		$redis = $this->redis;
		$prefix = $this->prefix;
		$prefix_length = strlen($prefix);
		$it = null;

		while(($keys = $redis->scan($it)))
		{
			foreach ($keys as $internal_key)
			{
				if (strpos($internal_key, $prefix) !== 0)
				{
					continue;
				}

				yield substr($internal_key, $prefix_length);
			}
		}
	}
}
