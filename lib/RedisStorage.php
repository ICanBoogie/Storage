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
	use ArrayAccessTrait;

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
	 * @param string $prefix
	 */
	public function __construct($redis, $prefix)
	{
		$this->redis = $redis;
		$this->prefix = $prefix;
	}

	/**
	 * @inheritdoc
	 */
	public function store($key, $value, $ttl = null)
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
	public function retrieve($key)
	{
		if (!$this->exists($key))
		{
			return null;
		}

		return unserialize($this->redis->get($this->prefix . $key));
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate($key)
	{
		$this->redis->delete($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		return $this->redis->exists($this->prefix . $key);
	}

	/**
	 * Clears the cache.
	 */
	public function clear()
	{
		$redis = $this->redis;
		$prefix = $this->prefix;
		$it = null;

		while($keys = $redis->scan($it))
		{
			foreach ($keys as $k)
			{
				if (strpos($k, $prefix) !== 0)
				{
					continue;
				}

				$this->redis->delete($k);
			}
		}
	}
}
