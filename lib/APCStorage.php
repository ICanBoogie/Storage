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
	use Storage\ArrayAccess;

	/**
	 * Whether the APC feature is available.
	 *
	 * @codeCoverageIgnore
	 */
	static public function is_available(): bool
	{
		return (extension_loaded('apc') || extension_loaded('apcu')) && ini_get('apc.enabled');
	}

	/**
	 * @var string
	 */
	private $prefix;

	public function __construct(string $prefix = null)
	{
		$this->prefix = $prefix ?: substr(sha1($_SERVER['DOCUMENT_ROOT']), 0, 8) . ':';
	}

	/**
	 * @inheritdoc
	 */
	public function exists(string $key): bool
	{
		return apcu_exists($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve(string $key)
	{
		$rc = apcu_fetch($this->prefix . $key, $success);

		return $success ? $rc : null;
	}

	/**
	 * @inheritdoc
	 */
	public function store(string $key, $data, int $ttl = null): void
	{
		apcu_store($this->prefix . $key, $data, $ttl);
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate(string $key): void
	{
		apcu_delete($this->prefix . $key);
	}

	/**
	 * @inheritdoc
	 */
	public function clear(): void
	{
		apcu_delete($this->create_internal_iterator());
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator(): iterable
	{
		$prefix_length = strlen($this->prefix);

		foreach ($this->create_internal_iterator() as $key => $dummy)
		{
			yield substr($key, $prefix_length);
		}
	}

	/**
	 * Creates internal iterator.
	 */
	private function create_internal_iterator(): \APCUIterator
	{
		return new \APCUIterator('/^' . preg_quote($this->prefix) . '/', APC_ITER_NONE);
	}
}
