<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\Storage;

/**
 * A trait for {@link \ICanBoogie\Storage\Storage} instances that are cleared using their iterator.
 */
trait ClearWithIterator
{
	abstract public function eliminate($key);

	/**
	 * @return \Iterator
	 */
	abstract public function getIterator();

	public function clear()
	{
		foreach ($this->getIterator() as $key)
		{
			$this->eliminate($key);
		}
	}
}
