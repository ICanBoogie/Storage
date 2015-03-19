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
 * A collection of {@link Storage} instances.
 *
 * @package ICanBoogie\Storage
 */
class StorageCollection extends CacheCollection implements Storage, \ArrayAccess
{
	use ArrayAccessTrait;

	/**
	 * @var Storage[]
	 */
	protected $collection = [];

	public function __construct(array $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * @inheritdoc
	 */
	public function store($key, $value, $ttl = null)
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve($key)
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
	public function eliminate($key)
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
		$this->for_each(__FUNCTION__, func_get_args());
	}

	/**
	 * Apply a same method to each storage instance in the collection.
	 *
	 * @param string $method
	 * @param array $arguments
	 */
	private function for_each($method, array $arguments)
	{
		foreach ($this->collection as $storage)
		{
			call_user_func_array([ $storage, $method ], $arguments);
		}
	}
}
