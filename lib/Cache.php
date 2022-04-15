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

use IteratorAggregate;
use Traversable;

/**
 * An interface for classes implementing cache capabilities.
 */
interface Cache extends IteratorAggregate
{
	/**
	 * Checks if a key exists in a storage.
	 */
	public function exists(string $key): bool;

	/**
	 * Retrieves a value.
	 *
	 * @return mixed The value associated with the key, or `null` if the key doesn't exists.
	 */
	public function retrieve(string $key): mixed;

	/**
	 * @inheritdoc
	 */
	public function getIterator(): Traversable;
}
