# (PHP) Explorable
<small>composer namespace: simplecomplex/**explorable**</small>

## Foreach'ing protected members made simple

An interface, base class and traits making it a breeze to expose a class'
protected members.

Extending `Explorable` facilitates:
- `isset()`
- `count()`
- `foreach (...`
- `var_dump()`
- `toObject()`, `toArray()`
- `json_encode()`

## Usage

### Class declaring it's properties in constant
Property names hardcoded in constant `EXPLORABLE_VISIBLE`.

```php
<?php

use SimpleComplex\Explorable\Explorable;

/**
 * @property-read string $foo
 * @property-read string $bar
 */
class ExplorablesDeclared extends Explorable
{
    public const EXPLORABLE_VISIBLE = [
        'foo' => true,
        'bar' => true,
    ];

    protected string $foo;

    protected string $bar;
}
```

### Class relying on property table discovery
The properties will be discovered on-demand, via `ExplorableByDiscovery`
constructor.

All instance vars must be nullable and declared as null
(`protected ?string $foo = null;`).<br>
Otherwise risk of getting "_::$foo must not be accessed before initialization_"
error, or the instance vars simply won't get discovered (because not set to a
value (null)).

```php
<?php

use SimpleComplex\Explorable\ExplorableByDiscovery;
use SimpleComplex\Explorable\ExplorableByDiscoveryTrait;

/**
 * @property-read string $foo
 * @property-read string $bar
 */
class ExplorablesDiscoverable extends Explorable
{
    use ExplorableByDiscoveryTrait;

    protected ?string $foo = null;

    protected ?string $bar = null;
}
```

## MIT licensed

[License and copyright](https://github.com/simplecomplex/php-explorable/blob/master/LICENSE).
[Explained](https://tldrlegal.com/license/mit-license).

## Requirements ##

- PHP ^7.4 || ^8.0

### Development (require-dev)

- phpunit ^8 || ^9