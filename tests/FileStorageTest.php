<?php

namespace ICanBoogie\Storage;

class FileStorageTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FileStorage
	 */
	static private $instance;

	static public function setupBeforeClass()
	{
		self::$instance = new FileStorage(__DIR__ . '/sandbox');
	}

	public function test_exists_undefined()
	{
		$this->assertFalse(self::$instance->exists('undefined'));
	}

	public function test_retrieve_undefined()
	{
		$this->assertNull(self::$instance->retrieve('undefined'));
	}

	public function test_store_simple()
	{
		$k = __FUNCTION__;
		$value = uniqid();

		self::$instance->store($k, $value);
		$this->assertEquals($value, self::$instance->retrieve($k));
		self::$instance->eliminate($k);
		$this->assertFalse(self::$instance->exists($k));
	}

	public function test_store_complex()
	{
		$k = __FUNCTION__;
		$value = [ uniqid(), uniqid(), uniqid() ];

		self::$instance->store($k, $value);
		$this->assertEquals($value, self::$instance->retrieve($k));
		self::$instance->eliminate($k);
		$this->assertFalse(self::$instance->exists($k));
	}
}
