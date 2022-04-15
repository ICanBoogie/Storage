<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\FileStorage;

use DirectoryIterator;

/**
 * Iterates through a file storage.
 *
 * The iterator is usually created using the {@link FileStorage::matching()} method.
 *
 * The iterator can also be used to delete matching keys.
 */
class Iterator implements \Iterator
{
	private \Iterator $iterator;

	public function __construct(\Iterator $iterator)
	{
		$this->iterator = $iterator;
	}

	/**
	 * Returns the directory iterator.
	 *
	 * Dot files are skipped.
	 */
	public function current(): DirectoryIterator
	{
		$file = $this->iterator->current();

		if ($file->isDot())
		{
			$this->iterator->next();

			$file = $this->current();
		}

		return $file;
	}

	public function next(): void
	{
		$this->iterator->next();
	}

	/**
	 * Returns the pathname of the key.
	 */
	public function key(): mixed
	{
		return $this->iterator->current()->getFilename();
	}

	public function valid(): bool
	{
		return $this->iterator->valid();
	}

	public function rewind(): void
	{
		$this->iterator->rewind();
	}

	/**
	 * Deletes the key found by the iterator.
	 */
	public function delete(): void
	{
		foreach ($this->iterator as $file)
		{
			unlink($file->getPathname());
		}
	}
}
