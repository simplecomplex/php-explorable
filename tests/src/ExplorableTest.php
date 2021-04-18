<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-time/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Tests\Explorable;

use PHPUnit\Framework\TestCase;


/**
 * @code
 * // CLI, in document root:
 * vendor/bin/phpunit --do-not-cache-result vendor/simplecomplex/explorable/tests/src/ExplorableTest.php
 * @endcode
 *
 * @package SimpleComplex\Tests\Explorable
 */
class ExplorableTest extends TestCase
{

    /**
     * Native get_object_vars() unfortunately cannot see the exposed
     * properties.
     * And casting to array is useless.
     * @see \get_object_vars()
     */
    public function testNativeGetObjectVars()
    {
        $o = new ExplorableSetOnce();
        $o->a = 'alpha';
        $o->b = 'beta';
        $o->c = 'gamma';

        $a = get_object_vars($o);
        static::assertIsArray($a, 'Is array');
        static::assertEmpty($a, 'Is empty');

        $a = (array) $o;
        static::assertIsArray($a, 'Is array');
        static::assertNotEmpty($a, 'Is not empty');
        static::assertSame(4, count($a), 'Has too many buckets');
        static::assertSame("\0*\0a\0*\0b\0*\0c\0*\0explorableCursor", join('', array_keys($a)), 'Keys are rubbish');
    }

    /**
     * Set-once setter must err on second attempt.
     * @see \SimpleComplex\Explorable\ExplorableSetOnceTrait::__set()
     */
    public function testSetOnce()
    {
        $o = new ExplorableSetOnce();
        $o->a = 'alpha';
        static::expectException(\RuntimeException::class);
        $o->a = 'alpha';
    }
    public function testSetOnceNull()
    {
        $o = new ExplorableSetOnce();
        $o->a = 'alpha';
        static::expectException(\RuntimeException::class);
        $o->a = null;
    }

    public function testExtension()
    {
        $extension = new Extension('the foo', 'the bar');
        static::assertEquals('the foo', $extension->foo);
        static::assertEquals('the bar', $extension->bar);
    }
}
