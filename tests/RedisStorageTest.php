<?php

namespace Test\ICanBoogie\Storage;

use ArrayAccess;
use ICanBoogie\Storage\RedisStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

class RedisStorageTest extends TestCase
{
    use TestStorageTrait;

    /**
     * @var Storage<string, mixed>&ArrayAccess<string, mixed>
     */
    private Storage&ArrayAccess $storage;

    protected function setUp(): void
    {
        if (!class_exists('Redis')) {
            $this->markTestSkipped('The Redis extension is not available.');
        }

        $redis = new \Redis();
        $redis->connect(
            host: getenv('ICANBOOGIE_REDIS_HOST') ?: '127.0.0.1',
            port: (int) (getenv('ICANBOOGIE_REDIS_PORT') ?: 6379)
        );
        $version = $redis->info()['redis_version'] ?? null;

        if (!is_string($version) || version_compare($version, '2.8', '<')) {
            $this->markTestSkipped('The Redis v2.8 or later is required.');
        }

        $this->storage = new RedisStorage($redis, 'prefix_' . substr(sha1(uniqid()), 0, 8) . ':');
    }
}
