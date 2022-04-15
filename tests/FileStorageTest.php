<?php

namespace Test\ICanBoogie\Storage;

use ICanBoogie\Storage\FileStorage;
use PHPUnit\Framework\TestCase;

final class FileStorageTest extends TestCase
{
	use TestStorageTrait;

	/**
	 * @var FileStorage
	 */
	private $storage;

	protected function setUp(): void
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
