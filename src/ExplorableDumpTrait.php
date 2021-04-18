<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2020 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-explorable/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Explorable;

/**
 * Trait providing dump methods, including \JsonSerializable implementation.
 *
 * @see Explorable
 *
 *
 * Tell IDE about apparantly absent properties.
 * @mixin Explorable
 *
 * @package SimpleComplex\Explorable
 */
trait ExplorableDumpTrait
{
    /**
     * Dumps publicly readable properties to standard object.
     *
     * Uses __get() method to support custom initialization/retrieval.
     * @see Explorable::__get()
     *
     * @param bool $recursive
     *
     * @return \stdClass
     */
    public function toObject(bool $recursive = false) : \stdClass
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        $o = new \stdClass();
        foreach ($this->explorableCursor as $key) {
            $value = $this->__get($key);
            if ($recursive && $value instanceof ExplorableInterface) {
                $o->{$key} = $value->toObject(true);
            }
            else {
                $o->{$key} = $value;
            }
        }
        return $o;
    }

    /**
     * Dumps publicly readable properties to array.
     *
     * Uses __get() method to support custom initialization/retrieval.
     * @see Explorable::__get()
     *
     * @param bool $recursive
     *
     * @return array
     */
    public function toArray(bool $recursive = false) : array
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        $a = [];
        foreach ($this->explorableCursor as $key) {
            $value = $this->__get($key);
            if ($recursive && $value instanceof ExplorableInterface) {
                $a[$key] = $value->toObject(true);
            }
            else {
                $a[$key] = $value;
            }
        }
        return $a;
    }

    /**
     * Make var_dump() make sense.
     *
     * @return array
     */
    public function __debugInfo() : array
    {
        // Erring explorable property shan't make this instance un-dumpable.
        try {
            return $this->toArray(true);
        }
        catch (\Throwable $ignore) {
            return $this->toArray();
        }
    }


    // JsonSerializable.--------------------------------------------------------

    /**
     * JSON serializes to object listing all publicly readable properties.
     *
     * @return object
     */
    public function jsonSerialize()
    {
        return $this->toObject(true);
    }
}
