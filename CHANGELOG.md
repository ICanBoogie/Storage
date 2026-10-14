# CHANGELOG

## v6.0.0

### New Requirements

- PHP 8.2+

### New features

- `Cache` and `Storage` are generic: `Storage<string, mixed>`. `getIterator()` yields the keys.

### Deprecated Features

None

### Backward Incompatible Changes

- `Cache::retrieve()` returns `mixed`, and `Cache::getIterator()` returns a `Traversable` (it was `iterable`).
  Implementations must follow.

### Other Changes

- The `read()` method of the `FileStorage` adapters returns `null` when the file cannot be read.
- `RedisStorage` is documented as expecting a `\Redis` instance.
- `FileStorage` takes an exclusive lock on the file it writes on platforms other than Windows, as it was meant to.
  The condition could never be true.
- `CacheCollection::getIterator()` returns an empty iterator for an empty collection, instead of failing.
- `APCStorage` no longer fails when `$_SERVER['DOCUMENT_ROOT']` is not defined.
- Removed `lib/polyfills.php`, which emulated functions that no supported PHP version has.
- Replaced PHP Code Sniffer with PHP-CS-Fixer, and added PHPStan at level `max`.
- Tooling updated: PHPUnit 11 to 13, PHPStan 2.3, CI tests the lowest and the latest dependencies on PHP 8.2 and the
  latest PHP, and the Redis and APCu extensions are installed in the Docker image.
