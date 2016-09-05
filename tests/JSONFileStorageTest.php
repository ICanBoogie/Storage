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

class JSONFileStorageTest extends \PHPUnit_Framework_TestCase
{
	use TestStorageTrait;

	/**
	 * @var FileStorage
	 */
	private $storage;

	public function setUp()
	{
		$this->storage = new JSONFileStorage(__DIR__ . '/sandbox/' . uniqid());
	}
}
