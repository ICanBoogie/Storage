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

/**
 * An interface for classes capable of encoding and decoding data when it is stored and
 * retrieved from a storage.
 */
interface Adapter
{
	/**
	 * @param string $filename
	 * @param mixed $data
	 *
	 * @return mixed
	 */
	public function write($filename, $data);

	/**
	 * @param string $filename
	 *
	 * @return mixed
	 */
	public function read($filename);
}
