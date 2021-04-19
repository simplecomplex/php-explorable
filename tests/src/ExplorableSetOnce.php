<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-explorable/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Tests\Explorable;

use SimpleComplex\Explorable\ExplorableBaseTrait;
use SimpleComplex\Explorable\ExplorableGetTrait;
use SimpleComplex\Explorable\ExplorableSetOnceTrait;
use SimpleComplex\Explorable\ExplorableDumpTrait;

/**
 * Trait providing setter which only allows properties to be set once.
 *
 * @see Explorable
 *
 *
 * Tell IDE about apparantly absent properties.
 * @mixin \SimpleComplex\Explorable\Explorable
 *
 *
 * @property string $a
 * @property string $b
 * @property string $c
 *
 * @package SimpleComplex\Explorable
 */
class ExplorableSetOnce
{

    //public const EXPLORABLE_VISIBLE = [];
    //public const EXPLORABLE_HIDDEN = [];

    use ExplorableBaseTrait;
    use ExplorableGetTrait;
    use ExplorableSetOnceTrait;
    use ExplorableDumpTrait;

    protected static array $explorableKeys = [];

    protected ?string $a = null;

    protected ?string $b = null;

    protected ?string $c = null;

}
