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

/**
 * @property Storage $storage
 */
trait TestStorageTrait
{
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

	public function test_store_with_ttl()
	{
		$storage = $this->storage;
		$storage->store($key = uniqid(), $value = uniqid(), $ttl = 1);
		$this->assertTrue($storage->exists($key));
		$this->assertSame($value, $storage->retrieve($key));
		sleep($ttl + 1);
		$this->assertFalse($storage->exists($key));
		$this->assertNull($storage->retrieve($key));
	}

	public function test_iterator()
	{
		$s = $this->storage;
		$j = 10;
		$k = [];

		for ($i = 0 ; $i < $j ; $i++)
		{
			$s[ $k[] = uniqid() ] = uniqid();
		}

		$kk = [];

		foreach ($s as $key)
		{
			$kk[] = $key;
		}

		sort($k);
		sort($kk);

		$this->assertEquals($k, $kk);
	}
}
