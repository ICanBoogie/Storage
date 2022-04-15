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

use ArrayAccess;

/**
 * A collection of {@link Storage} instances.
 */
class StorageCollection extends CacheCollection implements Storage, ArrayAccess
{
	use Storage\ArrayAccess;

	/**
	 * @inheritdoc
	 */
	public function retrieve(string $key): mixed
	{
		/* @var $update Storage[] */

		$value = null;
		$update = [];

		foreach ($this->collection as $storage)
		{
			$value = $storage->retrieve($key);

			if ($value !== null)
			{
				break;
			}

			$update[] = $storage;
		}

		if ($value === null)
		{
			return null;
		}

		foreach ($update as $storage)
		{
			$storage->store($key, $value);
		}

		return $value;
	}

	/**
	 * @inheritdoc
	 */
	public function store(string $key, mixed $value, int $ttl = null): void
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate(string $key): void
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * @inheritdoc
	 */
	public function clear(): void
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * Apply a same method to each storage instance in the collection.
	 */
	private function for_each(string $method, array $arguments): void
	{
		foreach ($this->collection as $storage)
		{
			$storage->$method(...$arguments);
		}
	}
}
