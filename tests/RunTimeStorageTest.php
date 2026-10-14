<?php

namespace Test\ICanBoogie\Storage;

use ArrayAccess;
use ICanBoogie\Storage\RunTimeStorage;
use ICanBoogie\Storage\Storage;
use PHPUnit\Framework\TestCase;

class RunTimeStorageTest extends TestCase
{
    use TestStorageTrait;

    /**
     * @var Storage<string, mixed>&ArrayAccess<string, mixed>
     */
    private Storage&ArrayAccess $storage;

    protected function setUp(): void
    {
        $this->storage = new RunTimeStorage();
    }
}
