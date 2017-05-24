<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;

/**
 * Data is encoded with `var_export()` and read with `require`.
 */
class PHPAdapter implements Adapter
{
	/**
	 * @inheritdoc
	 */
	public function write($filename, $data)
	{
		$code = var_export($data, true);
		$data = <<<EOT
<?php return $code;
EOT;

		return file_put_contents($filename, $data);
	}

	/**
	 * @inheritdoc
	 */
	public function read($filename)
	{
		return require $filename;
	}
}
