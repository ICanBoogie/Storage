<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;
use ICanBoogie\Storage\FileStorage\Adapter\SerializeAdapter;

class SerializeAdapterTest extends TestCase
{
	/**
	 * @inheritdoc
	 */
	protected function getAdapter(): Adapter
	{
		return new SerializeAdapter();
	}
}
