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
 * Extend to expose a set list of protected/public properties for getting,
 * counting and foreach'ing.
 *
 * @package SimpleComplex\Explorable
 */
abstract class Explorable implements ExplorableInterface
{
    /**
     * Optional list of properties accessible when getting, counting
     * and foreach'ing.
     *
     * Keys are property name, values may be anything.
     * Allows a child class to extend parent's list by doing
     * const EXPLORABLE_PROPERTIES = [
     *   'childvar' => true,
     * ] + ParentClass::EXPLORABLE_PROPERTIES;
     *
     * @var mixed[]
     */
    const EXPLORABLE_VISIBLE = [];

    /**
     * Optional list of hidden properties when getting, counting
     * and foreach'ing.
     *
     * Gets subtracted when constructor populates class var
     * explorableKeys[class].
     *
     * Keys are property name, values may be anything.
     * Allows a child class to extend parent's list by doing
     * const EXPLORABLE_PROPERTIES = [
     *   'childvar' => true,
     * ] + ParentClass::EXPLORABLE_PROPERTIES;
     *
     * @var mixed[]
     */
    const EXPLORABLE_HIDDEN = [];

    /**
     * List of names of properties accessible when count()'ing and foreach'ing,
     * by class name.
     *
     * @var string[]
     */
    protected static $explorableKeys = [
        // '\\SimpleComplex\\Explorable\\Explorable => [],
    ];

    /**
     * Copy of class var explorableKeys[class] used as cursor for iteration.
     *
     * @see Explorable::$explorableKeys
     *
     * @var string[]
     */
    protected $explorableCursor = [];


    /**
     * Prepares iteration cursor and class property list if they are empty.
     *
     * Extending class' constructor is free to define explorableKeys[class]
     * in a different manner.
     *
     * Does nothing if the instance iteration cursor is non-empty.
     * Otherwise copies class var explorableKeys[class] to explorableCursor.
     * @see Explorable::$explorableCursor
     * @see Explorable::$explorableKeys
     *
     * If class var explorableKeys[class] is non-existent:
     * Uses keys of class constant EXPLORABLE_PROPERTIES, unless empty.
     * Uses names of actual declared instance properties as fallback,
     * except for keys listed in class constant NON_EXPLORABLE_PROPERTIES.
     * @see Explorable::EXPLORABLE_VISIBLE
     * @see Explorable::EXPLORABLE_HIDDEN
     */
    public function __construct()
    {
        // No work if cursor already populated.
        if (!$this->explorableCursor) {
            // Try copying from class var; this instance may not be the first.
            $class = get_class();
            if (isset(static::$explorableKeys[$class])) {
                $keys = static::$explorableKeys[$class];
            }
            else {
                // This instance _is_ the first.
                // Try copying from class constant.
                $keys = array_keys(static::EXPLORABLE_VISIBLE);
                if (!$keys) {
                    // Fallback: use actual declared properties.
                    // get_object_vars() also includes unitialized (null) vars.
                    $keys = array_keys(get_object_vars($this));
                }
                // Subtract hidden properties.
                $keys = array_diff($keys, ['explorableCursor'], array_keys(static::EXPLORABLE_HIDDEN));
                // Save copy for class.
                static::$explorableKeys[$class] = $keys;
            }
            // Save copy for instance iteration.
            $this->explorableCursor = $keys;
        }
    }

    /**
     * Get protected property.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \OutOfBoundsException
     *      If no such instance property.
     */
    public function __get(string $key)
    {
        if (in_array($key, $this->explorableCursor)) {
            return $this->{$key};
        }
        throw new \OutOfBoundsException(get_class($this) . ' instance exposes no property[' . $key . '].');
    }

    /**
     * Attempt to set protected property.
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
        if (in_array($key, $this->explorableCursor)) {
            throw new \RuntimeException(get_class($this) . ' instance property[' . $key . '] is read-only.');
        }
        throw new \OutOfBoundsException(get_class($this) . ' instance exposes no property[' . $key . '].');
    }

    /**
     * Uses __get() method to support custom initialization/retrieval.
     * @see Explorable::__get()
     *
     * @param string|int $key
     *
     * @return bool
     *      False: no such property, or the value is null.
     */
    public function __isset($key) : bool
    {
        return in_array($key, $this->explorableCursor) && $this->__get($key) !== null;
    }

    // Countable.---------------------------------------------------------------

    /**
     * @see \Countable::count()
     *
     * @return int
     */
    public function count() : int
    {
        return count($this->explorableCursor);
    }


    // Foreachable (Iterator).--------------------------------------------------

    /**
     * @see \Iterator::rewind()
     *
     * @return void
     */
    public function rewind() : void
    {
        reset($this->explorableCursor);
    }

    /**
     * @see \Iterator::key()
     *
     * @return string
     */
    public function key() : string
    {
        return current($this->explorableCursor);
    }

    /**
     * Uses __get() method to support custom initialization/retrieval.
     * @see Explorable::__get()
     *
     * @see \Iterator::current()
     *
     * @return mixed
     */
    public function current()
    {
        return $this->__get(current($this->explorableCursor));
    }

    /**
     * @see \Iterator::next()
     *
     * @return void
     */
    public function next() : void
    {
        next($this->explorableCursor);
    }

    /**
     * @see \Iterator::valid()
     *
     * @return bool
     */
    public function valid() : bool
    {
        // The null check is cardinal; without it foreach runs out of bounds.
        $key = key($this->explorableCursor);
        return $key !== null && $key < count($this->explorableCursor);
    }


    // Dumping/casting.---------------------------------------------------------

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
