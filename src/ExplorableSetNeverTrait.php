<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-explorable/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Explorable;

/**
 * Trait providing protected member setter
 * which always denies setting.
 *
 * @see Explorable
 *
 *
 * Tell IDE about apparantly absent properties.
 * @mixin Explorable
 *
 * @package SimpleComplex\Explorable
 */
trait ExplorableSetNeverTrait
{
    /**
     * Denies setting protected property unconditionally.
     *
     * @param string $key
     * @param mixed|null $value
     *
     * @return void
     *
     * @throws \OutOfBoundsException
     *      If no such instance property.
     * @throws \RuntimeException
     *      If that instance property is read-only.
     */
    public function __set(string $key, $value)
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        if (in_array($key, $this->explorableCursor)) {
            throw new \RuntimeException(get_class($this) . ' instance property[' . $key . '] is read-only.');
        }
        throw new \OutOfBoundsException(get_class($this) . ' instance exposes no property[' . $key . '].');
    }
}
