# Storage

[![Release](https://img.shields.io/packagist/v/icanboogie/storage.svg)](https://github.com/ICanBoogie/Storage/releases)
[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage.svg)](http://travis-ci.org/ICanBoogie/Storage)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/Storage.svg)](https://scrutinizer-ci.com/g/ICanBoogie/Storage/?branch=master)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage.svg)](https://coveralls.io/r/ICanBoogie/Storage)
[![Packagist](https://img.shields.io/packagist/dt/icanboogie/storage.svg)](https://packagist.org/packages/icanboogie/storage)

**icanboogie/storage** defines an API to store and retrieve values, while offering different storage
backends.

Values can be stored using the runtime memory, [Redis][], [APC][], the file system… Storage
collections retrieve and store values using multiple different storage instances, that usually range
from the less expensive (and the more volatile) to the more expensive (and the more durable).

This package includes the following storage backends:

- [RunTimeStorage][]: Uses a PHP array.
- [RedisStorage][]: Uses a [Redis][] instance.
- [APCStorage][]: Uses [APC][] or [APCu][].
- [FileStorage][]: Uses the file system.
- [StorageCollection][]: Uses a collection of storage.





## Storage

A storage is used to store a value and retrieve it later. A unique key is used to identify the
value. Different storage store values using different mechanisms, and sometimes for a different
period of time. It's always a good idea to choose the storage that best fits a situation,
according to the persistence requirement and the expensiveness of the storage.

> **Note:** Storage classes implement the [Storage][] interface, including the
[StorageCollection][] class.

```php
<?php

use ICanBoogie\Storage\RunTimeStorage;

$storage = new RunTimeStorage;
$storage->exists('icanboogie');     // false
$storage->retrieve('icanboogie');   // null
$storage->store('icanboogie', "Yes Sir, I Can Boogie");
$storage->retrieve('icanboogie');   // "Yes Sir, I Can Boogie"
$storage->eliminate('icanboogie');
$storage->exists('icanboogie');     // false
$storage->retrieve('icanboogie');   // null
```

### Time To Live

The Time To Live (TTL) of an item is the amount of seconds between when that item is stored and it
is considered stale.

> **Warning:** [`apc.use_request_time`][] needs to be set to `false` if you want to use that feature
with APCU.

```php
<?php

use ICanBoogie\Storage\RunTimeStorage;

$storage = new RunTimeStorage;
$storage->store('icanboogie', "Yes Sir, I Can Boogie", $ttl = 3);
$storage->retrieve('icanboogie');   // "Yes Sir, I Can Boogie"
sleep(4);
$storage->exists('icanboogie');     // false
$storage->retrieve('icanboogie');   // null
```





### Use storage like arrays

Storage implements the `ArrayAccess` interface and may be accessed as arrays.

```php
<?php

use ICanBoogie\Storage\RunTimeStorage;

$storage = new RunTimeStorage;
isset($storage['icanboogie']);     // false
$storage['icanboogie'];            // null
$storage['icanboogie'] = "Yes Sir, I Can Boogie";
$storage['icanboogie'];            // "Yes Sir, I Can Boogie"
unset($storage['icanboogie']);
isset($storage['icanboogie']);     // false
$storage['icanboogie'];            // null
```





### Iterate storage keys

Storage implements the `IteratorAggregate` interface and may be used in a `foreach` to
iterate their keys:

```php
<?php

$storage['one'] = 1;
$storage['two'] = 2;
$storage['three'] = 3;

foreach ($storage as $key)
{
    echo "defined: $key\n";
}
```

```
defined: one
defined: two
defined: three
```





### Adapter

The [FileStorage][] storage uses _adapters_ to write and read data written to the filesystem. Any
class implementing the [Adapter][] interface can be used, the following are provided with the
package:

- [SerializeAdapter][]: Uses `serialize()` and `unserialize()` to encode and decode data. It is used
by default.

- [JSONAdapter][]: Uses `json_encode()` and `json_decode()` to encode and decode data.

- [PHPAdapter][]: Uses `var_export()` and `require` to encode and read data.





## Storage collection

When implementing caches, it's always a good idea to use a collection of [Storage][] instances that
range from the less expensive (and the more volatile) to the more expensive (and the more durable).

The following example demonstrates how a storage collection can be created with multiple
storage instances:

```php
<?php

use ICanBoogie\Storage\StorageCollection;
use ICanBoogie\Storage\RunTimeStorage;
use ICanBoogie\Storage\APCStorage;
use ICanBoogie\Storage\RedisStorage;
use ICanBoogie\Storage\FileStorage;

$storage = new StorageCollection([

    new RunTimeStorage,
    new APCStorage('my-prefix'),
    new RedisStorage($redis_client, 'my-prefix'),
    new FileStorage('/path/to/directory')

]);
```

All the storage instances in the collection are updated by the `store()`, `eliminate()`,
and `clear()` methods. Storage instances are also updated when values are retrieved from
_more expensive_ storages, so that the next time the value is requested it is retrieved from
a less expensive storage.





## Cache and Cache collection

The [Cache][] interface and [CacheCollection][] class implement a subset of the [Storage][]
interface and [StorageCollection][] class, they only provide read-only features.





## Support functions

- `APCStorage::is_available()` may be used to check if APC is available.





----------





## Requirements

The package requires PHP 7.1 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/):

    $ composer require icanboogie/storage





## Documentation

The package is documented as part of the [ICanBoogie][] framework
[documentation][]. The documentation is generated by
[ApiGen](http://apigen.org/), using the `make doc` command, in the `build/docs` directory.
The directory can later be cleaned with the `make clean` command.





## Local development & Testing

For local development, use the provided test container. You'll need [Docker][] for that. Run `make
test-container` to open a terminal session inside the container, then run `make test` to run the
test suite. Alternatively, run `make test-coverage` to run the test suite and produce coverage
report in `build/coverag`.

[Travis CI](http://about.travis-ci.org/) continuously test the package.

[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage.svg)](https://travis-ci.org/ICanBoogie/Storage)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage.svg)](https://coveralls.io/r/ICanBoogie/Storage)





## License

**icanboogie/storage** is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.





[APC]: http://php.net/manual/en/book.apc.php
[APCu]: https://github.com/krakjoe/apcu
[ICanBoogie]: https://github.com/ICanBoogie/ICanBoogie
[Redis]: http://redis.io/

[documentation]:     https://icanboogie.org/api/storage/master/
[APCStorage]:        https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.APCStorage.html
[Cache]:             https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.Cache.html
[CacheCollection]:   https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.CacheCollection.html
[FileStorage]:       https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.FileStorage.html
[Adapter]:           https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.FileStorage.Adapter.html
[JSONAdapter]:       https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.FileStorage.Adapter.JSONAdapter.html
[SerializeAdapter]:  https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.FileStorage.Adapter.SerializeAdapter.html
[PHPAdapter]:        https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.FileStorage.Adapter.PHPAdapter.html
[RedisStorage]:      https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.RedisStorage.html
[RunTimeStorage]:    https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.RunTimeStorage.html
[Storage]:           https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.Storage.html
[ArrayAccess]:       https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.Storage.ArrayAccess.html
[StorageCollection]: https://icanboogie.org/api/storage/master/class-ICanBoogie.Storage.StorageCollection.html
[Docker]:            https://www.docker.com/
[`apc.use_request_time`]: https://www.php.net/manual/en/apcu.configuration.php#ini.apcu.use-request-time
