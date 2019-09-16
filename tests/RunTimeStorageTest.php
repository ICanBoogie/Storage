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

use PHPUnit\Framework\TestCase;

class RunTimeStorageTest extends TestCase
{
	use TestStorageTrait;

	/**
	 * @var Storage
	 */
	private $storage;

	public function setUp()
	{
		$this->storage = new RunTimeStorage;
	}
}
