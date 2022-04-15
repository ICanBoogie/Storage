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

use ICanBoogie\Storage\RedisStorage;
use ICanBoogie\Storage\RunTimeStorage;
use ICanBoogie\Storage\Storage;
use ICanBoogie\Storage\StorageCollection;
use Iterator;
use PHPUnit\Framework\TestCase;

class StorageCollectionTest extends TestCase
{
	use TestStorageTrait;

	private Storage $s1;
	private Storage $s2;
	private Storage $s3;
	private StorageCollection $storage;

	protected function setUp(): void
	{
		$this->s1 = $s1 = new RunTimeStorage();
		$this->s2 = $s2 = new RunTimeStorage();
		$this->s3 = $s3 = new RunTimeStorage();
		$this->storage = new StorageCollection([ $s1, $s2, $s3 ]);
	}

	public function test_store()
	{
		$key = uniqid();
		$value = uniqid();

		$this->storage->store($key, $value);

		/* @var $storage Storage */

		foreach ([ $this->storage, $this->s1, $this->s2, $this->s3 ] as $storage) {
			$this->assertTrue($storage->exists($key));
			$this->assertSame($value, $storage->retrieve($key));
		}
	}

	public function test_update_up()
	{
		$s1 = $this->s1;
		$s2 = $this->s2;
		$s3 = $this->s3;
		$collection = $this->storage;
		$key = uniqid();
		$value = uniqid();

		$s3->store($key, $value);
		$this->assertSame($value, $s3->retrieve($key));
		$this->assertFalse($s2->exists($key));
		$this->assertFalse($s1->exists($key));
		$this->assertSame($value, $collection->retrieve($key));
		$this->assertSame($value, $s2->retrieve($key));
		$this->assertSame($value, $s1->retrieve($key));
	}

	public function test_eliminate()
	{
		$s1 = $this->s1;
		$s2 = $this->s2;
		$s3 = $this->s3;
		$collection = $this->storage;
		$k1 = uniqid();
		$v1 = uniqid();
		$k2 = uniqid();
		$v2 = uniqid();
		$k3 = uniqid();
		$v3 = uniqid();

		$collection->store($k1, $v1);
		$s2->store($k2, $v2);
		$s3->store($k3, $v3);

		$this->assertSame($v1, $s1->retrieve($k1));
		$this->assertSame($v1, $s2->retrieve($k1));
		$this->assertSame($v1, $s3->retrieve($k1));
		$collection->eliminate($k1);
		$this->assertFalse($s1->exists($k1));
		$this->assertFalse($s2->exists($k1));
		$this->assertFalse($s3->exists($k1));
		$this->assertNull($s1->retrieve($k1));
		$this->assertNull($s2->retrieve($k1));
		$this->assertNull($s3->retrieve($k1));
		$this->assertSame($v2, $s2->retrieve($k2));
		$this->assertSame($v3, $s3->retrieve($k3));
	}

	public function test_clear()
	{
		$s1 = $this->s1;
		$s2 = $this->s2;
		$s3 = $this->s3;
		$collection = $this->storage;
		$k1 = uniqid();
		$v1 = uniqid();
		$k2 = uniqid();
		$v2 = uniqid();
		$k3 = uniqid();
		$v3 = uniqid();

		$collection->store($k1, $v1);
		$s2->store($k2, $v2);
		$s3->store($k3, $v3);

		$collection->clear();
		$this->assertFalse($s1->exists($k1));
		$this->assertFalse($s2->exists($k1));
		$this->assertFalse($s3->exists($k1));
		$this->assertFalse($s2->exists($k2));
		$this->assertFalse($s3->exists($k3));
	}

	public function test_array_access()
	{
		$collection = $this->storage;
		$k = uniqid();
		$v = uniqid();

		$collection[$k] = $v;
		$this->assertTrue(isset($collection[$k]));
		$this->assertSame($v, $collection[$k]);

		unset($collection[$k]);
		$this->assertFalse(isset($collection[$k]));
		$this->assertNull($collection[$k]);
	}

	public function test_find_by_type()
	{
		$this->assertSame($this->s1, $this->storage->find_by_type(RunTimeStorage::class));
	}

	public function test_find_by_type_undefined()
	{
		$this->assertNull($this->storage->find_by_type(RedisStorage::class));
	}

	public function test_iterator()
	{
		$this->assertInstanceOf(Iterator::class, $this->storage->getIterator());
	}
}
