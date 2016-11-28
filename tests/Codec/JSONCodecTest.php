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

class JSONCodecTest extends \PHPUnit_Framework_TestCase
{
	public function testEncode()
	{
		$data = [ uniqid() => uniqid() ];
		$codec = new JSONCodec();

		$this->assertSame(json_encode($data), $codec->encode($data));
	}

	public function testDecode()
	{
		$data = json_encode([ uniqid() => uniqid() ]);
		$codec = new JSONCodec();

		$this->assertSame(json_decode($data, true), $codec->decode($data));
	}
}
