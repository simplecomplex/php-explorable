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
 * Relies on discovery of declared instance vars.
 * @see ExplorableByDiscovery::__construct()
 *
 * IMPORTANT: Extending class _must_ declare it's own
 * protected static array $explorableKeys;
 * And grand child class must do the same, to prevent sharing property table
 * with parent class (ExplorableByDiscovery immediate descendant).
 *
 * Prepares lazily; on external attempt to access or iterate.
 *
 * @package SimpleComplex\Explorable
 */
abstract class ExplorableByDiscovery extends Explorable
{
    /**
     * Optional list of hidden properties when getting, counting
     * and foreach'ing.
     *
     * Gets subtracted when constructor populates class var explorableKeys.
     *
     * Keys are property names, values may be anything.
     * Allows a child class to extend parent's list by doing
     * const EXPLORABLE_HIDDEN = [
     *   'childvar' => true,
     * ] + ParentClass::EXPLORABLE_HIDDEN;
     *
     * @var array
     */
    public const EXPLORABLE_HIDDEN = [];

    /**
     * List of names of properties accessible when count()'ing and foreach'ing.
     *
     * Shared by all instances of a class, but only populated once.
     *
     * IMPORTANT: Extending class must override, declaring _protected_:
     * protected static array $explorableKeys = [];
     * Otherwise parent and child would end up using the same list,
     * leaving parent or child with wrong property list.
     *
     * Is private to force child class override.
     *
     * @var string[]
     */
    private static array $explorableKeys;


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
     */
    public function __construct()
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
                $keys = array_keys(static::EXPLORABLE_VISIBLE);
                if (!$keys) {
                    // Fallback: use actual declared properties.
                    // get_object_vars() also includes unitialized (null) vars.
                    $keys = array_keys(get_object_vars($this));
                }
                // Subtract hidden properties.
                $keys = array_diff($keys, ['explorableCursor'], array_keys(static::EXPLORABLE_HIDDEN));
                // Save copy for class.
                static::$explorableKeys = $keys;
            }
            // Save copy for instance iteration.
            $this->explorableCursor = $keys;
        }
        parent::__construct();
    }
}
