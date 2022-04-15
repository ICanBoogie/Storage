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
use DirectoryIterator;
use Exception;
use ICanBoogie\Storage\FileStorage\Adapter;
use ICanBoogie\Storage\FileStorage\Adapter\SerializeAdapter;
use ICanBoogie\Storage\FileStorage\Iterator;
use RegexIterator;
use Traversable;

/**
 * A storage using the file system.
 */
final class FileStorage implements Storage, ArrayAccess
{
	use Storage\ArrayAccess;
	use Storage\ClearWithIterator;

	static private bool $release_after = false;

	/**
	 * Absolute path to the storage directory.
	 */
	private string $path;

	private Adapter $adapter;

	/**
	 * @param string $path Absolute path to the storage directory.
	 */
	public function __construct(string $path, Adapter $adapter = null)
	{
		$this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->adapter = $adapter ?? new SerializeAdapter;

		if (self::$release_after === null) {
			self::$release_after = !(str_starts_with(PHP_OS, 'WIN'));
		}
	}

	/**
	 * @inheritdoc
	 */
	public function exists(string $key): bool
	{
		$pathname = $this->format_pathname($key);
		$ttl_mark = $this->format_pathname_with_ttl($pathname);

		if (file_exists($ttl_mark) && fileatime($ttl_mark) < time() || !file_exists($pathname)) {
			return false;
		}

		return file_exists($pathname);
	}

	/**
	 * @inheritdoc
	 */
	public function retrieve(string $key): mixed
	{
		if (!$this->exists($key)) {
			return null;
		}

		return $this->read($this->format_pathname($key));
	}

	/**
	 * @inheritdoc
	 *
	 * @throws Exception when a file operation fails.
	 */
	public function store(string $key, mixed $value, int $ttl = null): void
	{
		$this->check_writable();

		$pathname = $this->format_pathname($key);
		$ttl_mark = $this->format_pathname_with_ttl($pathname);

		if ($ttl) {
			$future = time() + $ttl;

			touch($ttl_mark, $future, $future);
		} elseif (file_exists($ttl_mark)) {
			unlink($ttl_mark);
		}

		if ($value === true) {
			touch($pathname);

			return;
		}

		if ($value === null) {
			$this->eliminate($key);

			return;
		}

		set_error_handler(function () {
		});

		try {
			$this->safe_store($pathname, $value);
		} finally {
			restore_error_handler();
		}
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate(string $key): void
	{
		$pathname = $this->format_pathname($key);

		if (!file_exists($pathname)) {
			return;
		}

		unlink($pathname);
	}

	/**
	 * Normalizes a key into a valid filename.
	 */
	private function normalize_key(string $key): string
	{
		return str_replace('/', '--', $key);
	}

	/**
	 * Formats a key into an absolute pathname.
	 */
	private function format_pathname(string $key): string
	{
		return $this->path . $this->normalize_key($key);
	}

	/**
	 * Formats a pathname with a TTL extension.
	 */
	private function format_pathname_with_ttl(string $pathname): string
	{
		return $pathname . '.ttl';
	}

	private function read(string $pathname): mixed
	{
		return $this->adapter->read($pathname);
	}

	private function write(string $pathname, mixed $value): void
	{
		$this->adapter->write($pathname, $value);
	}

	/**
	 * Safely store the value.
	 *
	 * @throws Exception if an error occurs.
	 */
	private function safe_store(string $pathname, mixed $value): void
	{
		$dir = dirname($pathname);
		$uniqid = uniqid(mt_rand(), true);
		$tmp_pathname = $dir . '/var-' . $uniqid;
		$garbage_pathname = $dir . '/garbage-var-' . $uniqid;

		#
		# We lock the file create/update, but we write the data in a temporary file, which is then
		# renamed once the data is written.
		#

		$fh = fopen($pathname, 'a+');

		if (!$fh) {
			throw new Exception("Unable to open $pathname.");
		}

		if (self::$release_after && !flock($fh, LOCK_EX)) {
			throw new Exception("Unable to get to exclusive lock on $pathname.");
		}

		$this->write($tmp_pathname, $value);

		#
		# Windows, this is for you
		#
		if (!self::$release_after) {
			fclose($fh);
		}

		if (!rename($pathname, $garbage_pathname)) {
			throw new Exception("Unable to rename $pathname as $garbage_pathname.");
		}

		if (!rename($tmp_pathname, $pathname)) {
			throw new Exception("Unable to rename $tmp_pathname as $pathname.");
		}

		if (!unlink($garbage_pathname)) {
			throw new Exception("Unable to delete $garbage_pathname.");
		}

		#
		# Unix, this is for you
		#
		if (self::$release_after) {
			flock($fh, LOCK_UN);
			fclose($fh);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function getIterator(): Traversable
	{
		if (!is_dir($this->path)) {
			return;
		}

		$iterator = new DirectoryIterator($this->path);

		foreach ($iterator as $file) {
			if ($file->isDot() || $file->isDir()) {
				continue;
			}

			yield $file->getFilename();
		}
	}

	/**
	 * Returns an iterator for the keys matching a specified regex.
	 */
	public function matching(string $regex): iterable
	{
		return new Iterator(new RegexIterator(new DirectoryIterator($this->path), $regex));
	}

	private ?bool $is_writable = null;

	/**
	 * Checks whether the storage directory is writable.
	 *
	 * @throws Exception when the storage directory is not writable.
	 */
	public function check_writable(): bool
	{
		if ($this->is_writable) {
			return true;
		}

		$path = $this->path;

		if (!file_exists($path)) {
			set_error_handler(function () {
			});
			mkdir($path, 0705, true);
			restore_error_handler();
		}

		if (!is_writable($path)) {
			throw new Exception("The directory $path is not writable.");
		}

		return $this->is_writable = true;
	}
}
