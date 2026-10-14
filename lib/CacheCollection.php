<?php

/*
 * This file is part of the ICanBoogie package.
 *
 * (c) Olivier Laviale <olivier.laviale@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ICanBoogie\Storage;

use EmptyIterator;
use Traversable;

/**
 * A collection of {@link Cache} instances.
 *
 * @template TCache of Cache<string, mixed>
 *
 * @implements Cache<string, mixed>
 */
class CacheCollection implements Cache
{
    /**
     * @var array<TCache>
     */
    protected array $collection = [];

    /**
     * @param array<TCache> $collection
     */
    public function __construct(array $collection)
    {
        $this->collection = $collection;
    }

    /**
     * @inheritdoc
     */
    public function exists(string $key): bool
    {
        foreach ($this->collection as $cache) {
            if ($cache->exists($key)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    public function retrieve(string $key): mixed
    {
        foreach ($this->collection as $cache) {
            if ($cache->exists($key)) {
                return $cache->retrieve($key);
            }
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getIterator(): Traversable
    {
        $first = reset($this->collection);

        if ($first === false) {
            return new EmptyIterator();
        }

        return $first->getIterator();
    }

    /**
     * Finds a cache by type.
     *
     * @param string $type The class or interface of the storage to find.
     *
     * @return TCache|null The cache matching the specified type or `null` if none match.
     */
    public function find_by_type(string $type): ?Cache
    {
        foreach ($this->collection as $cache) {
            if ($cache instanceof $type) {
                return $cache;
            }
        }

        return null;
    }
}
