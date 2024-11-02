<?php

namespace Test\ICanBoogie\Storage\FileStorage\Adapter;

use ICanBoogie\Storage\FileStorage\Adapter;
use ICanBoogie\Storage\FileStorage\Adapter\JSONAdapter;

class JSONAdapterTest extends TestCase
{
    protected function getAdapter(): Adapter
    {
        return new JSONAdapter();
    }
}
