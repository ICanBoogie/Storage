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

class APCStorageTest extends \PHPUnit_Framework_TestCase
{
	use TestStorageTrait;

	/**
	 * @var Storage
	 */
	private $storage;

	public function setUp()
	{
		if (!function_exists('apc_store'))
		{
			$this->markTestSkipped('The APC or APCu extension is not available.');
		}

		$this->storage = new APCStorage('prefix_' . substr(sha1(uniqid()), 0, 8) . '__');
	}
}
