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

use Exception;

/**
 * A trait for storage implementing {@link \ArrayAccess}.
 */
trait ArrayAccess
{
	abstract public function store(string $key, mixed $value, int $ttl = null);
	abstract public function retrieve(string $key): mixed;
	abstract public function exists(string $key): bool;
	abstract public function eliminate(string $key): void;

	/**
	 * Alias to {@link store()}.
	 *
	 * @param string $key
	 *
	 * @throws Exception
	 */
	public function offsetSet(mixed $key, mixed $value): void
	{
		$this->store($key, $value);
	}

	/**
	 * Alias to {@link exists()}.
	 *
	 * @param string $key
	 */
	public function offsetExists(mixed $key): bool
	{
		return $this->exists($key);
	}

	/**
	 * Alias to {@link eliminate()}.
	 *
	 * @param string $key
	 */
	public function offsetUnset(mixed $key): void
	{
		$this->eliminate($key);
	}

	/**
	 * Alias to {@link retrieve()}.
	 *
	 * @param string $key
	 */
	public function offsetGet(mixed $key): mixed
	{
		return $this->retrieve($key);
	}
}
