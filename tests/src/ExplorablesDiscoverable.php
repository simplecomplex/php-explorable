<?php
/**
 * SimpleComplex PHP Explorable
 * @link      https://github.com/simplecomplex/php-explorable
 * @copyright Copyright (c) 2017-2021 Jacob Friis Mathiasen
 * @license   https://github.com/simplecomplex/php-time/blob/master/LICENSE (MIT License)
 */
declare(strict_types=1);

namespace SimpleComplex\Tests\Explorable;

use SimpleComplex\Explorable\Explorable;
use SimpleComplex\Explorable\ExplorableTrait;

/**
 * @property-read ?int $dit
 * @property-read ?int $dat
 *
 * @package SimpleComplex\Tests\Explorable
 */
class ExplorablesDiscoverable extends Explorable
{
    use ExplorableTrait;

    protected ?int $dit = null;

    protected ?int $dat = null;

    public function populate(int $dit, int $dat)
    {
        $this->dit = $dit;
        $this->dat = $dat;
    }

}
