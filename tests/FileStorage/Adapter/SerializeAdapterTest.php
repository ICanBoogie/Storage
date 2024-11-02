<?php

namespace Test\ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;
use ICanBoogie\Storage\FileStorage\Adapter\SerializeAdapter;

class SerializeAdapterTest extends TestCase
{
    protected function getAdapter(): Adapter
    {
        return new SerializeAdapter();
    }
}
