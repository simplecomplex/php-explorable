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
 * Extend to expose a list of protected/public properties for getting,
 * counting, foreach'ing and JSON-serialization.
 *
 * Extending class must declare accessible properties in class constant
 * EXPLORABLE_VISIBLE.
 *
 * @package SimpleComplex\Explorable
 */
abstract class Explorable implements ExplorableInterface
{
    /**
     * List of publicly accessible properties.
     *
     * Keys are property names, values may be anything.
     * Allows a child class to extend parent's list by doing
     * const EXPLORABLE_VISIBLE = [
     *   'childvar' => true,
     * ] + ParentClass::EXPLORABLE_VISIBLE;
     *
     * @var array
     */
    public const EXPLORABLE_VISIBLE = [];

    /**
     * List of publicly accessible property names used for lookup and iteration.
     *
     * @var string[]
     */
    protected array $explorableCursor = [];


    /**
     * Prepares inner iteration cursor.
     */
    public function __construct()
    {
        if (!$this->explorableCursor) {
            $this->explorableCursor = array_keys(static::EXPLORABLE_VISIBLE);
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
