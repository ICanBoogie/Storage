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
 * A collection of {@link Cache} instances.
 *
 * @package ICanBoogie\Storage
 */
class CacheCollection implements Cache
{
	/**
	 * @var Cache[]
	 */
	protected $collection = [];

	public function __construct(array $collection)
	{
		$this->collection = $collection;
	}

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		foreach ($this->collection as $storage)
		{
			if ($storage->exists($key))
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve($key)
	{
		foreach ($this->collection as $storage)
		{
			if ($storage->exists($key))
			{
				return $storage->retrieve($key);
			}
		}

		return null;
	}
}
