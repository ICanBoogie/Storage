<?php

namespace Test\ICanBoogie\Storage;

use ICanBoogie\Storage\APCStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

final class APCStorageTest extends TestCase
{
    use TestStorageTrait;

    private Storage $storage;

    protected function setUp(): void
    {
        $this->storage = new APCStorage('prefix_' . substr(sha1(uniqid()), 0, 8) . '__');
    }
}
