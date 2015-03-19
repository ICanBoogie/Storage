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
 * A storage using APC.
 */
class APCStorage implements Storage, \ArrayAccess
{
	use ArrayAccessTrait;

	private $prefix;

	public function __construct($prefix = null)
	{
		$this->prefix = $prefix ?: substr(sha1($_SERVER['DOCUMENT_ROOT']), 0, 8);
	}

	/**
	 * @inheritdoc
	 */
	public function store($key, $data, $ttl = 0)
	{
		apc_store($this->prefix . $key, $data, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve($key)
	{
		$rc = apc_fetch($this->prefix . $key, $success);

		return $success ? $rc : null;
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate($key)
	{
		apc_delete($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
		$iterator = new \APCIterator('user', '/^' . preg_quote($this->prefix) . '/', APC_ITER_NONE);

		foreach ($iterator as $key => $dummy)
		{
			apc_delete($key);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		return apc_exists($this->prefix . $key);
	}
}
