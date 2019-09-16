<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\Storage;

/**
 * A trait for storage implementing {@link \ArrayAccess}.
 */
trait ArrayAccess
{
	abstract public function store(string $key, $value, int $ttl = null);
	abstract public function retrieve(string $key);
	abstract public function exists(string $key): bool;
	abstract public function eliminate(string $key): void;

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
