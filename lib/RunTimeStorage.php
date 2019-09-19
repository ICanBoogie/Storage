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
 */
class RunTimeStorage implements Storage, \ArrayAccess
{
	use Storage\ArrayAccess;

	/**
	 * @var array<string, mixed>
	 */
	private $values = [];

	/**
	 * @var array<string, int|null>
	 */
	private $until = [];

	/**
	 * @inheritdoc
	 */
	public function exists(string $key): bool
	{
		if (isset($this->until[$key]) && $this->until[$key] < time()) {
			return false;
		}

		return array_key_exists($key, $this->values);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve(string $key)
	{
		return $this->exists($key) ? $this->values[$key] : null;
	}

	/**
	 * @inheritdoc
	 */
	public function store(string $key, $value, int $ttl = null): void
	{
		$this->values[$key] = $value;
		$this->until[$key] = time() + $ttl;
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate(string $key): void
	{
		unset($this->values[$key]);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(): void
	{
		$this->values = [];
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator(): iterable
	{
		return new \ArrayIterator(array_keys($this->values));
	}
}
