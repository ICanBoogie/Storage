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

	public function test_storage()
	{
		$k1 = uniqid();
		$v1 = uniqid();
		$k2 = uniqid();
		$v2 = uniqid();
		$s = $this->storage;

		$this->assertFalse($s->exists($k1));
		$this->assertFalse($s->exists($k2));
		$this->assertNull($s->retrieve($k1));
		$this->assertNull($s->retrieve($k2));

		$s->store($k1, $v1);
		$s->store($k2, $v2);
		$this->assertTrue($s->exists($k1));
		$this->assertTrue($s->exists($k2));
		$this->assertSame($v1, $s->retrieve($k1));
		$this->assertSame($v2, $s->retrieve($k2));

		$s->eliminate($k1);
		$this->assertFalse($s->exists($k1));
		$this->assertTrue($s->exists($k2));
		$this->assertNull($s->retrieve($k1));
		$this->assertSame($v2, $s->retrieve($k2));

		$s->clear();
		$this->assertFalse($s->exists($k1));
		$this->assertFalse($s->exists($k2));
		$this->assertNull($s->retrieve($k1));
		$this->assertNull($s->retrieve($k2));
	}
}
