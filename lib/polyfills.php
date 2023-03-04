<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (!function_exists('apcu_store'))
{
	function apcu_store($key, $var, $ttl = 0)
	{
		return apc_store($key, $var, $ttl);
	}
}

if (!function_exists('apcu_exists'))
{
	function apcu_exists($keys)
	{
		return apc_exists($keys);
	}
}

if (!function_exists('apcu_fetch'))
{
	function apcu_fetch($key, &$success = null)
	{
		return apc_fetch($key, $success);
	}
}

if (!function_exists('apcu_delete'))
{
	function apcu_delete($key)
	{
		if ($key instanceof APCUIterator)
		{
			$key = array_keys(iterator_to_array($key));
		}

		if (is_array($key))
		{
			$failure = false;

			foreach ($key as $k)
			{
				if (apc_delete($k) === false)
				{
					$failure = true;
				}
			}

			return !$failure;
		}

		return apc_delete($key);
	}
}

if (!class_exists(APCUIterator::class, false) && class_exists(APCIterator::class, false))
{
	class APCUIterator extends APCIterator
	{
		public function __construct($search = null, $format = APC_ITER_ALL, $chunk_size = 100, $list = APC_LIST_ACTIVE)
		{
			parent::__construct('user', $search, $format, $chunk_size, $list);
		}
	}
}
