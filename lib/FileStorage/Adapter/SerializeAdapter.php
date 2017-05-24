<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;

/**
 * Data is encoded and decoded with `serialize()` and `unserialize()`.
 */
class SerializeAdapter implements Adapter
{
	/**
	 * @inheritdoc
	 */
	public function write($filename, $data)
	{
		return file_put_contents($filename, serialize($data));
	}

	/**
	 * @inheritdoc
	 */
	public function read($filename)
	{
		return unserialize(file_get_contents($filename));
	}
}
