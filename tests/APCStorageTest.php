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

use ICanBoogie\Storage\APCStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

final class APCStorageTest extends TestCase
{
	use TestStorageTrait;

	/**
	 * @var Storage
	 */
	private $storage;

	protected function setUp(): void
	{
		if (!APCStorage::is_available()) {
			$this->markTestSkipped('The APC or APCu extension is not available.');
		}

		$this->storage = new APCStorage('prefix_' . substr(sha1(uniqid()), 0, 8) . '__');
	}
}
