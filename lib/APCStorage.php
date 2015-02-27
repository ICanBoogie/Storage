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
class APCStorage implements StorageInterface
{
	private $master_key;

	public function __construct()
	{
		$this->master_key = md5($_SERVER['DOCUMENT_ROOT']);
	}

	/**
	 * @inheritdoc
	 */
	public function store($key, $data, $ttl=0)
	{
		apc_store($this->master_key . $key, $data, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve($key)
	{
		$rc = apc_fetch($this->master_key . $key, $success);

		return $success ? $rc : null;
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate($key)
	{
		apc_delete($this->master_key . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function clear()
	{
		apc_clear_cache('user');
	}

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		return apc_exists($this->master_key . $key);
	}
}
