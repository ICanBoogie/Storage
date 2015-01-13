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
	/**
	 * Alias to {@link store()}.
	 */
	public function offsetSet($key, $value)
	{
		$this->store($key, $value);
	}

	/**
	 * Alias to {@link exists()}.
	 */
	public function offsetExists($key)
	{
		return $this->exists($key);
	}

	/**
	 * Alias to {@link eliminate()}.
	 */
	public function offsetUnset($key)
	{
		$this->eliminate($key);
	}

	/**
	 * Alias to {@link retrieve()}.
	 */
	public function offsetGet($key)
	{
		return $this->retrieve($key);
	}
}
