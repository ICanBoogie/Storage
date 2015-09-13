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

trait ClearWithIterator
{
	abstract function eliminate($key);
	abstract function getIterator();

	public function clear()
	{
		foreach ($this as $key)
		{
			$this->eliminate($key);
		}
	}
}
