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

use ICanBoogie\Storage\Codec\SerializeCodec;

/**
 * A storage using the file system.
 */
class FileStorage implements Storage, \ArrayAccess
{
	use Storage\ArrayAccess;
	use Storage\ClearWithIterator;

	static private $release_after;

	/**
	 * Absolute path to the storage directory.
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * @var Codec
	 */
	private $codec;

	/**
	 * Constructor.
	 *
	 * @param string $path Absolute path to the storage directory.
	 * @param Codec $codec
	 */
	public function __construct($path, Codec $codec = null)
	{
		$this->path = rtrim($path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
		$this->codec = $codec ?: new SerializeCodec;

		if (self::$release_after === null)
		{
			self::$release_after = strpos(PHP_OS, 'WIN') === 0 ? false : true;
		}
	}

	/**
	 * @inheritdoc
	 */
	public function exists($key)
	{
		return file_exists($this->format_pathname($key));
	}

	/**
	 * @inheritdoc
	 *
	 * @param mixed $default The value returned if the key does not exists. Defaults to `null`.
	 */
	public function retrieve($key, $default = null)
	{
		$this->check_writable();

		$pathname = $this->format_pathname($key);
		$ttl_mark = $this->format_pathname_with_ttl($pathname);

		if (file_exists($ttl_mark) && fileatime($ttl_mark) < time() || !file_exists($pathname))
		{
			return $default;
		}

		return $this->unserialize(file_get_contents($pathname));
	}

	/**
	 * @inheritdoc
	 *
	 * @throws \Exception when a file operation fails.
	 */
	public function store($key, $value, $ttl = 0)
	{
		$this->check_writable();

		$pathname = $this->format_pathname($key);
		$ttl_mark = $this->format_pathname_with_ttl($pathname);

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
			$this->eliminate($key);

			return;
		}

		set_error_handler(function() {});

		try
		{
			$this->safe_store($pathname, $this->serialize($value));
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
	 * @inheritdoc
	 */
	public function eliminate($key)
	{
		$pathname = $this->format_pathname($key);

		if (!file_exists($pathname))
		{
			return;
		}

		unlink($pathname);
	}

	/**
	 * Normalizes a key into a valid filename.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	protected function normalize_key($key)
	{
		return str_replace('/', '--', $key);
	}

	/**
	 * Formats a key into an absolute pathname.
	 *
	 * @param string $key
	 *
	 * @return string
	 */
	protected function format_pathname($key)
	{
		return $this->path . $this->normalize_key($key);
	}

	/**
	 * Formats a pathname with a TTL extension.
	 *
	 * @param string $pathname
	 *
	 * @return string
	 */
	protected function format_pathname_with_ttl($pathname)
	{
		return $pathname . '.ttl';
	}

	/**
	 * Serializes a value so that it can be stored.
	 *
	 * @param mixed $value
	 *
	 * @return string
	 */
	protected function serialize($value)
	{
		return $this->codec->encode($value);
	}

	/**
	 * Unserializes a value retrieved from storage.
	 *
	 * @param string $value
	 *
	 * @return mixed
	 */
	protected function unserialize($value)
	{
		return $this->codec->decode($value);
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
	 */
	public function getIterator()
	{
		if (!is_dir($this->path))
		{
			return;
		}

		$iterator = new \DirectoryIterator($this->path);

		foreach ($iterator as $file)
		{
			if ($file->isDot() || $file->isDir())
			{
				continue;
			}

			yield $file->getFilename();
		}
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
