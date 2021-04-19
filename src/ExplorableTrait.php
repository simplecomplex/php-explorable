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
 * Trait for completing a class that extends Explorable.
 *
 * @see Explorable
 *
 * Tell IDE about apparantly absent properties.
 * @mixin Explorable
 *
 * @package SimpleComplex\Explorable
 */
trait ExplorableTrait
{
    /**
     * List of names of properties accessible when count()'ing and foreach'ing.
     *
     * Shared by all instances of a class, but only populated once.
     *
     * @var string[]
     */
    protected static array $explorableKeys = [];
}
