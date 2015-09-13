<?php

namespace ICanBoogie\Storage;

class FileStorageTest extends \PHPUnit_Framework_TestCase
{
	use TestStorageTrait;

	/**
	 * @var FileStorage
	 */
	private $storage;

	public function setUp()
	{
		$this->storage = new FileStorage(__DIR__ . '/sandbox/' . uniqid());
	}

	public function test_exists_undefined()
	{
		$this->assertFalse($this->storage->exists('undefined'));
	}

	public function test_retrieve_undefined()
	{
		$this->assertNull($this->storage->retrieve('undefined'));
	}

	public function test_store_simple()
	{
		$k = __FUNCTION__;
		$value = uniqid();

		$this->storage->store($k, $value);
		$this->assertEquals($value, $this->storage->retrieve($k));
		$this->storage->eliminate($k);
		$this->assertFalse($this->storage->exists($k));
	}

	public function test_store_complex()
	{
		$k = __FUNCTION__;
		$value = [ uniqid(), uniqid(), uniqid() ];

		$this->storage->store($k, $value);
		$this->assertEquals($value, $this->storage->retrieve($k));
		$this->storage->eliminate($k);
		$this->assertFalse($this->storage->exists($k));
	}
}
