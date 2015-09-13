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

class CacheCollectionTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var CacheCollection
	 */
	private $collection;

	public function setUp()
	{
		$c1 = $this
			->getMockBuilder(Cache::class)
			->setMethods([ 'exists', 'retrieve' ])
			->getMockForAbstractClass();
		$c1
			->expects($this->any())
			->method('exists')
			->willReturnCallback(function($v) {

				return $v === 'one';

			});
		$c1
			->expects($this->any())
			->method('retrieve')
			->willReturnCallback(function($v) {

				return $v === 'one' ? 1 : null;

			});

		$c2 = $this
			->getMockBuilder(Cache::class)
			->setMethods([ 'exists', 'retrieve' ])
			->getMockForAbstractClass();
		$c2
			->expects($this->any())
			->method('exists')
			->willReturnCallback(function($v) {

				return $v === 'two';

			});
		$c2
			->expects($this->any())
			->method('retrieve')
			->willReturnCallback(function($v) {

				return $v === 'two' ? 2 : null;

			});

		$c3 = $this
			->getMockBuilder(Cache::class)
			->setMethods([ 'exists', 'retrieve' ])
			->getMockForAbstractClass();
		$c3
			->expects($this->any())
			->method('exists')
			->willReturnCallback(function($v) {

				return $v === 'three';

			});
		$c3
			->expects($this->any())
			->method('retrieve')
			->willReturnCallback(function($v) {

				return $v === 'three' ? 3 : null;

			});

		$this->collection = new CacheCollection([ $c1, $c2, $c3 ]);
	}

	public function test_exists()
	{
		$collection = $this->collection;
		$this->assertTrue($collection->exists('one'));
		$this->assertTrue($collection->exists('two'));
		$this->assertTrue($collection->exists('three'));
		$this->assertFalse($collection->exists(uniqid()));
	}

	public function test_retrieve()
	{
		$collection = $this->collection;
		$this->assertEquals(1, $collection->retrieve('one'));
		$this->assertEquals(2, $collection->retrieve('two'));
		$this->assertEquals(3, $collection->retrieve('three'));
		$this->assertNull($collection->retrieve(uniqid()));
	}
}
