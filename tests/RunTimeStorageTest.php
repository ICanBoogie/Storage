<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\ICanBoogie\Storage;

use ICanBoogie\Storage\RunTimeStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

class RunTimeStorageTest extends TestCase
{
	use TestStorageTrait;

	private Storage $storage;

	protected function setUp(): void
	{
		$this->storage = new RunTimeStorage();
	}
}
