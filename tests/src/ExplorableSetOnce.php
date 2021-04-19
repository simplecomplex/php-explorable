<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-explorable/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Tests\Explorable;

use SimpleComplex\Explorable\ExplorableByDiscovery;
use SimpleComplex\Explorable\ExplorableByDiscoveryTrait;
use SimpleComplex\Explorable\ExplorableSetOnceTrait;

/**
 * Trait providing setter which only allows properties to be set once.
 *
 * @see ExplorableByDiscovery
 *
 * @property string $a
 * @property string $b
 * @property string $c
 *
 * @package SimpleComplex\Explorable
 */
class ExplorableSetOnce extends ExplorableByDiscovery
{
    use ExplorableByDiscoveryTrait;
    use ExplorableSetOnceTrait;

    protected ?string $a = null;

    protected ?string $b = null;

    protected ?string $c = null;

}
