<?php

namespace Test\ICanBoogie\Storage;

use ICanBoogie\Storage\RunTimeStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

class RunTimeStorageTest extends TestCase
{
    use TestStorageTrait;

    private Storage $storage;

    protected function setUp(): void
    {
        $this->storage = new RunTimeStorage();
    }
}
