<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\Codec;

class SerializeCodecTest extends \PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data = [ uniqid() => uniqid() ];
		$codec = new SerializeCodec();

		$this->assertSame(serialize($data), $codec->encode($data));
	}

	public function testDecode()
	{
		$data = serialize([ uniqid() => uniqid() ]);
		$codec = new SerializeCodec();

		$this->assertSame(unserialize($data), $codec->decode($data));
	}
}
