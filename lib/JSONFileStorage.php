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

class JSONFileStorage extends FileStorage
{
	/**
	 * Serialize the value using `json_encode()`.
	 *
	 * @inheritdoc
	 */
	protected function serialize($value)
	{
		return json_encode($value);
	}

	/**
	 * Unserialize the value using `json_decode()`.
	 *
	 * @inheritdoc
	 */
	protected function unserialize($value)
	{
		return json_decode($value, true);
	}
}
