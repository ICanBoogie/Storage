<?php

namespace Test\ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;
use ICanBoogie\Storage\FileStorage\Adapter\PHPAdapter;

class PHPAdapterTest extends TestCase
{
    protected function getAdapter(): Adapter
    {
        return new PHPAdapter();
    }
}
