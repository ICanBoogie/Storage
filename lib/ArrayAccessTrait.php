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
 * A trait for storage implementing {@link \ArrayAccess}.
 *
 * @package ICanBoogie\Storage
 */
trait ArrayAccessTrait
{
	abstract public function store($key, $value, $ttl = null);
	abstract public function retrieve($key);
	abstract public function exists($key);
	abstract public function eliminate($key);

	/**
	 * Alias to {@link store()}.
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function offsetSet($key, $value)
	{
		$this->store($key, $value);
	}

	/**
	 * Alias to {@link exists()}.
	 *
	 * @param string $key
	 *
	 * @return bool
	 */
	public function offsetExists($key)
	{
		return $this->exists($key);
	}

	/**
	 * Alias to {@link eliminate()}.
	 *
	 * @param string $key
	 */
	public function offsetUnset($key)
	{
		$this->eliminate($key);
	}

	/**
	 * Alias to {@link retrieve()}.
	 *
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function offsetGet($key)
	{
		return $this->retrieve($key);
	}
}
