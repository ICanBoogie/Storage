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
 * A storage using the file system.
 */
class FileStorage implements Storage, \ArrayAccess, \IteratorAggregate
{
	use ArrayAccessTrait;

	/**
	 * Magic pattern used to recognized automatically serialized values.
	 *
	 * @var string
	 */
	const MAGIC = "VAR\0SLZ\0";

	/**
	 * Length of the magic pattern {@link MAGIC}.
	 *
	 * @var int
	 */
	const MAGIC_LENGTH = 8;

	static private $release_after;

	/**
	 * Absolute path to the storage directory.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Constructor.
	 *
	 * @param string $path Absolute path to the storage directory.
	 */
	public function __construct($path)
	{
		$this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

		if (self::$release_after === null)
		{
			self::$release_after = strpos(PHP_OS, 'WIN') === 0 ? false : true;
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception when a file operation fails.
	 */
	public function store($key, $value, $ttl=0)
	{
		$this->check_writable();

		$pathname = $this->path . $key;
		$ttl_mark = $pathname . '.ttl';

		if ($ttl)
		{
			$future = time() + $ttl;

			touch($ttl_mark, $future, $future);
		}
		elseif (file_exists($ttl_mark))
		{
			unlink($ttl_mark);
		}

		if ($value === true)
		{
			touch($pathname);

			return;
		}

		if ($value === false || $value === null)
		{
			$this->offsetUnset($key);

			return;
		}

		#
		# If the value is an array or a string it is serialized and prepended with a magic
		# identifier.
		#

		if (is_array($value) || is_object($value))
		{
			$value = self::MAGIC . serialize($value);
		}

		set_error_handler(function() {});

		try
		{
			$this->safe_store($pathname, $value);
		}
		catch (\Exception $e)
		{
			throw $e;
		}
		finally
		{
			restore_error_handler();
		}
	}

	/**
	 * Safely store the value.
	 *
	 * @param $pathname
	 * @param $value
	 *
	 * @throws \Exception if an error occurs.
	 */
	private function safe_store($pathname, $value)
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

		if (!$fh)
		{
			throw new \Exception("Unable to open $pathname.");
		}

		if (self::$release_after && !flock($fh, LOCK_EX))
		{
			throw new \Exception("Unable to get to exclusive lock on $pathname.");
		}

		file_put_contents($tmp_pathname, $value);

		#
		# Windows, this is for you
		#
		if (!self::$release_after)
		{
			fclose($fh);
		}

		if (!rename($pathname, $garbage_pathname))
		{
			throw new \Exception("Unable to rename $pathname as $garbage_pathname.");
		}

		if (!rename($tmp_pathname, $pathname))
		{
			throw new \Exception("Unable to rename $tmp_pathname as $pathname.");
		}

		if (!unlink($garbage_pathname))
		{
			throw new \Exception("Unable to delete $garbage_pathname.");
		}

		#
		# Unix, this is for you
		#
		if (self::$release_after)
		{
			flock($fh, LOCK_UN);
			fclose($fh);
		}
	}

	/**
	 * @inheritdoc
	 *
	 * @param mixed $default The value returned if the key does not exists. Defaults to `null`.
	 */
	public function retrieve($name, $default=null)
	{
		$this->check_writable();

		$pathname = $this->path . $name;
		$ttl_mark = $pathname . '.ttl';

		if (file_exists($ttl_mark) && fileatime($ttl_mark) < time() || !file_exists($pathname))
		{
			return $default;
		}

		$value = file_get_contents($pathname);

		if (substr($value, 0, self::MAGIC_LENGTH) == self::MAGIC)
		{
			$value = unserialize(substr($value, self::MAGIC_LENGTH));
		}

		return $value;
	}

	/**
	 * @inheritdoc
	 */
	public function eliminate($name)
	{
		$pathname = $this->path . $name;

		if (!file_exists($pathname))
		{
			return;
		}

		unlink($pathname);
	}

	/**
	 * @inheritdoc
	 */
	public function exists($name)
	{
		return file_exists($this->path . $name);
	}

	public function clear()
	{
		throw new \Exception("The method clear() is not implemented");
	}

	/**
	 * Returns an iterator for the storage.
	 *
	 * @return FileStorageIterator
	 */
	public function getIterator()
	{
		return new FileStorageIterator(new \DirectoryIterator($this->path));
	}

	/**
	 * Returns an iterator for the keys matching a specified regex.
	 *
	 * @param string $regex
	 *
	 * @return FileStorageIterator
	 */
	public function matching($regex)
	{
		return new FileStorageIterator(new \RegexIterator(new \DirectoryIterator($this->path), $regex));
	}

	private $is_writable;

	/**
	 * Checks whether the storage directory is writable.
	 *
	 * @return bool
	 *
	 * @throws \Exception when the storage directory is not writable.
	 */
	public function check_writable()
	{
		if ($this->is_writable)
		{
			return true;
		}

		$path = $this->path;

		if (!file_exists($path))
		{
			set_error_handler(function() {});
			mkdir($path, 0705, true);
			restore_error_handler();
		}

		if (!is_writable($path))
		{
			throw new \Exception("The directory $path is not writable.");
		}

		return $this->is_writable = true;
	}
}
