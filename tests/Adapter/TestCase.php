<?php

namespace ICanBoogie\Storage\Adapter;

use ICanBoogie\Storage\Adapter;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @return Adapter
	 */
	abstract protected function getAdapter();

	public function testAdapter()
	{
		$data = [ uniqid() => uniqid() ];
		$adapter = $this->getAdapter();
		$filename = $this->createFilename();

		$adapter->write($filename, $data);
		$this->assertFileExists($filename);
		$this->assertSame($data, $adapter->read($filename));
	}

	/**
	 * @return string
	 */
	private function createFilename()
	{
		return \ICanBoogie\Storage\SANDBOX_DIR . DIRECTORY_SEPARATOR . uniqid();
	}
}
