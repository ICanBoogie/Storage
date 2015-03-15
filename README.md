# Storage

[![Release](https://img.shields.io/packagist/v/icanboogie/storage.svg)](https://github.com/ICanBoogie/Storage/releases)
[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage/master.svg)](http://travis-ci.org/ICanBoogie/Storage)
[![HHVM](https://img.shields.io/hhvm/icanboogie/storage.svg)](http://hhvm.h4cc.de/package/icanboogie/storage)
[![Code Quality](https://img.shields.io/scrutinizer/g/ICanBoogie/Storage/master.svg)](https://scrutinizer-ci.com/g/ICanBoogie/Storage)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage/master.svg)](https://coveralls.io/r/ICanBoogie/Storage)
[![Packagist](https://img.shields.io/packagist/dt/icanboogie/storage.svg)](https://packagist.org/packages/icanboogie/storage)

The package defines an API to store values, and provides an implementation for a filesystem
storage.





----------





## Requirements

The package requires PHP 5.4 or later.





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
[documentation](http://icanboogie.org/docs/). You can generate the documentation for the package and its dependencies with the `make doc` command. The documentation is generated in the `build/docs` directory. [ApiGen](http://apigen.org/) is required. The directory can later be cleaned with the `make clean` command.





## Testing

The test suite is ran with the `make test` command. [PHPUnit](https://phpunit.de/) and [Composer](http://getcomposer.org/) need to be globally available to run the suite. The command installs dependencies as required. The `make test-coverage` command runs test suite and also creates an HTML coverage report in "build/coverage". The directory can later be cleaned with the `make clean` command.

The package is continuously tested by [Travis CI](http://about.travis-ci.org/).

[![Build Status](https://img.shields.io/travis/ICanBoogie/Storage/master.svg)](https://travis-ci.org/ICanBoogie/Storage)
[![Code Coverage](https://img.shields.io/coveralls/ICanBoogie/Storage/master.svg)](https://coveralls.io/r/ICanBoogie/Storage)





## License

**ICanBoogie/Storage** is licensed under the New BSD License - See the [LICENSE](LICENSE) file for details.
