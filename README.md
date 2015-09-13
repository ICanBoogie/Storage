# Storage

[![Release](https://img.shields.io/packagist/v/icanboogie/storage.svg)](https://github.com/ICanBoogie/Storage/releases)
[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage/1.2.svg)](http://travis-ci.org/ICanBoogie/Storage)
[![HHVM](https://img.shields.io/hhvm/icanboogie/storage.svg)](http://hhvm.h4cc.de/package/icanboogie/storage)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/Storage/1.2.svg)](https://scrutinizer-ci.com/g/ICanBoogie/Storage/?branch=1.2)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage/1.2.svg)](https://coveralls.io/r/ICanBoogie/Storage)
[![Packagist](https://img.shields.io/packagist/dt/icanboogie/storage.svg)](https://packagist.org/packages/icanboogie/storage)

The **icanboogie/storage** package defines an API to store and retrieve values, while offering
different storage backends.

Values can be stored using the runtime memory, [Redis][], [APC][], the file system…
Storage collections are used to retrieve and store values using multiple different
storage instances, that usually range from the less expensive (and the more volatile) to the
more expensive (and the more durable).

The following storages are included in this package:

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





### Use storages like arrays

Storage implement the `ArrayAccess` interface and may be accessed as arrays.

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

Storage implement the `IteratorAggregate` interface and may be used in a `foreach` to
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

The package requires PHP 5.5 or later.





## Installation

The recommended way to install this package is through [Composer](http://getcomposer.org/):

```
composer require icanboogie/storage
```





### Cloning the repository

The package is [available on GitHub](https://github.com/ICanBoogie/Storage), its repository can be
cloned with the following command line:

	$ git clone https://github.com/ICanBoogie/Storage.git





## Documentation

The package is documented as part of the [ICanBoogie][] framework
[documentation][]. The documentation is generated by
[ApiGen](http://apigen.org/), using the `make doc` command, in the `build/docs` directory.
The directory can later be cleaned with the `make clean` command.





## Testing

The test suite is ran with the `make test` command. [PHPUnit](https://phpunit.de/) and
[Composer](http://getcomposer.org/) need to be globally available to run the suite.
The command installs dependencies as required. The `make test-coverage` command runs test suite and
also creates an HTML coverage report in "build/coverage". The directory can later be cleaned with
the `make clean` command.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage/master.svg)](https://travis-ci.org/ICanBoogie/Storage)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage/master.svg)](https://coveralls.io/r/ICanBoogie/Storage)





## License

**icanboogie/storage** is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.





[APC]: http://php.net/manual/en/book.apc.php
[APCu]: https://github.com/krakjoe/apcu
[ArrayAccessTrait]: http://icanboogie.org/docs/namespace-ICanBoogie.Storage.ArrayAccessTrait.html
[ICanBoogie]: https://github.com/ICanBoogie/ICanBoogie
[Redis]: http://redis.io/

[documentation]:     http://api.icanboogie.org/storage/1.2/
[APCStorage]:        http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.APCStorage.html
[Cache]:             http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.Cache.html
[Cachecollection]:   http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.CacheCollection.html
[FileStorage]:       http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.FileStorage.html
[RedisStorage]:      http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.RedisStorage.html
[RunTimeStorage]:    http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.RunTimeStorage.html
[Storage]:           http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.Storage.html
[StorageCollection]: http://api.icanboogie.org/storage/1.2/class-ICanBoogie.Storage.StorageCollection.html
