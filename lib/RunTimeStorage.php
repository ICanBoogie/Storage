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
 * A storage that uses an array to store values.
 *
 * @package ICanBoogie\Storage
 */
class RunTimeStorage implements Storage
{
	private $values = [];

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		return array_key_exists($key, $this->values);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve($key)
	{
		return $this->exists($key) ? $this->values[$key] : null;
	}

	/**
	 * @inheritdoc
	 */
	public function store($key, $value, $ttl = null)
	{
		$this->values[$key] = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate($key)
	{
		unset($this->values[$key]);
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
		$this->values = [];
	}
}
