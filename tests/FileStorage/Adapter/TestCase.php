<?php

namespace ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;
use const ICanBoogie\Storage\SANDBOX_DIR;

abstract class TestCase extends \PHPUnit\Framework\TestCase
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
		return SANDBOX_DIR . DIRECTORY_SEPARATOR . uniqid();
	}
}
