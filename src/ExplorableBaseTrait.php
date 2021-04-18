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
 * Trait providing means for explorable properties discovery
 * plus \Countable, \Iterator implementation.
 *
 * Using class may declare class constants:
 * const EXPLORABLE_VISIBLE = [];
 * const EXPLORABLE_HIDDEN = [];
 *
 * @see Explorable
 *
 *
 * Tell IDE about apparantly absent properties.
 * @mixin Explorable
 *
 * @package SimpleComplex\Explorable
 */
trait ExplorableBaseTrait
{
//    /**
//     * Optional list of properties accessible when getting, counting
//     * and foreach'ing.
//     *
//     * Keys are property names, values may be anything.
//     * Allows a child class to extend parent's list by doing
//     * const EXPLORABLE_VISIBLE = [
//     *   'childvar' => true,
//     * ] + ParentClass::EXPLORABLE_VISIBLE;
//     *
//     * @var mixed[]
//     */
//    public const EXPLORABLE_VISIBLE = [];
//
//    /**
//     * Optional list of hidden properties when getting, counting
//     * and foreach'ing.
//     *
//     * Gets subtracted when constructor populates class var explorableKeys.
//     *
//     * Keys are property names, values may be anything.
//     * Allows a child class to extend parent's list by doing
//     * const EXPLORABLE_HIDDEN = [
//     *   'childvar' => true,
//     * ] + ParentClass::EXPLORABLE_HIDDEN;
//     *
//     * @var mixed[]
//     */
//    public const EXPLORABLE_HIDDEN = [];

    /**
     * List of names of properties accessible when count()'ing and foreach'ing,
     * by class name.
     *
     * Shared by all instances of a class, but only populated once.
     *
     * IMPORTANT: Extending class must override, declaring _protected_:
     * protected static array $explorableKeys = [];
     * Otherwise parent and child would end up using the same list,
     * leaving parent or child with wrong property list.
     *
     * @var string[]
     */
    protected static array $explorableKeys = [];

    /**
     * Copy of class var explorableKeys used as cursor for iteration.
     *
     * @see Explorable::$explorableKeys
     *
     * @var string[]
     */
    protected array $explorableCursor = [];


    /**
     * Prepares iteration cursor and class property list if they are empty.
     *
     * Does nothing if the instance iteration cursor is non-empty.
     * Otherwise copies class var explorableKeys to explorableCursor.
     * @see Explorable::$explorableCursor
     * @see Explorable::$explorableKeys
     *
     * If class var explorableKeys is null:
     * Uses keys of class constant EXPLORABLE_VISIBLE, unless empty.
     * Uses names of actual declared instance properties as fallback.
     * Subtracts keys listed in class constant EXPLORABLE_HIDDEN.
     * @see Explorable::EXPLORABLE_VISIBLE
     * @see Explorable::EXPLORABLE_HIDDEN
     *
     * Unlike the Explorable class ditto this method does not require neither
     * EXPLORABLE_VISIBLE nor EXPLORABLE_HIDDEN.
     * @see Explorable::explorablePrepare()
     */
    protected function explorablePrepare() : void
    {
        // No work if cursor already populated.
        if (!$this->explorableCursor) {
            // Try copying from class var; this instance may not be the first.
            if (static::$explorableKeys) {
                // Uses child class override.
                $keys = static::$explorableKeys;
            }
            else {
                // This instance _is_ the first.
                // Try copying from class constant.
                $keys = defined('static::EXPLORABLE_VISIBLE') ? array_keys(static::EXPLORABLE_VISIBLE) : [];
                if (!$keys) {
                    // Fallback: use actual declared properties.
                    // get_object_vars() also includes unitialized (null) vars.
                    $keys = array_keys(get_object_vars($this));
                }
                // Subtract hidden properties.
                if (defined('static::EXPLORABLE_HIDDEN')) {
                    $keys = array_diff($keys, ['explorableCursor'], array_keys(static::EXPLORABLE_HIDDEN));
                }
                else {
                    $keys = array_diff($keys, ['explorableCursor']);
                }

                // Save copy for class.
                static::$explorableKeys = $keys;
            }
            // Save copy for instance iteration.
            $this->explorableCursor = $keys;
        }
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
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

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
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

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
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        reset($this->explorableCursor);
    }

    /**
     * @see \Iterator::key()
     *
     * @return string
     */
    public function key() : string
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

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
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        return $this->__get(current($this->explorableCursor));
    }

    /**
     * @see \Iterator::next()
     *
     * @return void
     */
    public function next() : void
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        next($this->explorableCursor);
    }

    /**
     * @see \Iterator::valid()
     *
     * @return bool
     */
    public function valid() : bool
    {
        // Important: do same lazy preparation in overriding method.
        if (!$this->explorableCursor) {
            $this->explorablePrepare();
        }

        // The null check is cardinal; without it foreach runs out of bounds.
        $key = key($this->explorableCursor);
        return $key !== null && $key < count($this->explorableCursor);
    }
}
