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
 * Iterates through a file storage.
 *
 * The iterator is usually created using the {@link FileStorage::matching()} method.
 *
 * The iterator can also be used to delete matching keys.
 */
class FileStorageIterator implements \Iterator
{
	/**
	 * Iterator.
	 *
	 * @var \Iterator
	 */
	protected $iterator;

	public function __construct(\Iterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * Returns the directory iterator.
	 *
	 * Dot files are skipped.
	 *
	 * @return \DirectoryIterator
	 */
	public function current()
	{
		$file = $this->iterator->current();

		if ($file->isDot())
		{
			$this->iterator->next();

			$file = $this->current();
		}

		return $file;
	}

	public function next()
	{
		$this->iterator->next();
	}

	/**
	 * Returns the pathname of the key.
	 *
	 * @return string
	 */
	public function key()
	{
		return $this->iterator->current()->getFilename();
	}

	public function valid()
	{
		return $this->iterator->valid();
	}

	public function rewind()
	{
		$this->iterator->rewind();
	}

	/**
	 * Deletes the key found by the iterator.
	 */
	public function delete()
	{
		foreach ($this->iterator as $file)
		{
			unlink($file->getPathname());
		}
	}
}
