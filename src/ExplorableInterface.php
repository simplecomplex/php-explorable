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
 * Extend to expose a set list of protected/public properties for counting
 * and foreach'ing.
 *
 * @package SimpleComplex\Explorable
 */
interface ExplorableInterface extends \Countable, \Iterator /*~ Traversable*/, \JsonSerializable
{
    /**
     * Get protected property.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \OutOfBoundsException
     *      No such instance property.
     */
    public function __get(string $key);

    /**
     * Attempt to set protected property.
     *
     * @param string $key
     * @param mixed|null $value
     *
     * @return void
     *
     * @throws \OutOfBoundsException
     *      No such instance property.
     * @throws \RuntimeException
     *      Instance property is read-only.
     */
    public function __set(string $key, $value);

    /**
     * @param string $key
     *
     * @return bool
     */
    public function __isset(string $key) : bool;


    // Countable.---------------------------------------------------------------

    /**
     * @see \Countable::count()
     *
     * @return int
     */
    public function count() : int;


    // Foreachable (Iterator).--------------------------------------------------

    /**
     * @see \Iterator::rewind()
     *
     * @return void
     */
    public function rewind() : void;

    /**
     * @see \Iterator::key()
     *
     * @return string
     */
    public function key() : string;

    /**
     * @see \Iterator::current()
     *
     * @return mixed
     */
    public function current();

    /**
     * @see \Iterator::next()
     *
     * @return void
     */
    public function next() : void;

    /**
     * @see \Iterator::valid()
     *
     * @return bool
     */
    public function valid() : bool;


    // Dumping/casting.---------------------------------------------------------

    /**
     * Dumps publicly readable properties to standard object.
     *
     * @param bool $recursive
     *      True: use child explorable's toObject method.
     *
     * @return \stdClass
     */
    public function toObject(bool $recursive = false) : \stdClass;

    /**
     * Dumps publicly readable properties to array.
     *
     * @param bool $recursive
     *      True: use child explorable's toArray method.
     *
     * @return array
     */
    public function toArray(bool $recursive = false) : array;


    // JsonSerializable.--------------------------------------------------------

    /**
     * Probably returns object listing all publicly readable properties.
     *
     * Implementations are allowed to return other than object;
     * in some cases - like datetimes - it may make sense to serialize to string
     * (or other).
     *
     * @return object|mixed
     */
    public function jsonSerialize();
}
