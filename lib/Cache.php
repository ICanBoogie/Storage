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
 * An interface for classes implementing cache capabilities.
 */
interface Cache extends \IteratorAggregate
{
	/**
	 * Checks if a key exists in a storage.
	 *
	 * @param $key
	 *
	 * @return bool `true` if the key exists, `false` otherwise.
	 */
	public function exists($key);

	/**
	 * Retrieves a value.
	 *
	 * @param string $key The key of the value.
	 *
	 * @return mixed|null The value associated with the key, or `null` if the key doesn't exists.
	 */
	public function retrieve($key);

	/**
	 * @inheritdoc
	 *
	 * @return \Iterator
	 */
	public function getIterator();
}
