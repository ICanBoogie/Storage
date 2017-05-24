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
 * Date is encoded and decoded with `json_encode()` and `json_decode()`.
 */
class JSONAdapter implements Adapter
{
	/**
	 * @inheritdoc
	 */
	public function write($filename, $data)
	{
		return file_put_contents($filename, json_encode($data));
	}

	/**
	 * @inheritdoc
	 */
	public function read($filename)
	{
		return json_decode(file_get_contents($filename), true);
	}
}
