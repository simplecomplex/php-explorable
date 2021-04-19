<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-time/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Tests\Explorable;

use SimpleComplex\Explorable\Explorable;
use SimpleComplex\Explorable\ExplorableTrait;

/**
 * @property-read string $foo
 * @property-read string $bar
 *
 * @package SimpleComplex\Tests\Explorable
 */
class ExplorablesDeclared extends Explorable
{
    use ExplorableTrait;

    public const EXPLORABLE_VISIBLE = [
        'foo' => true,
        'bar' => true,
    ];

    protected string $foo;

    protected string $bar;

    public function populate(string $foo, string $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }

}
