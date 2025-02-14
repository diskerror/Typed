<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\Scalar\TInteger;

/**
 * Created by PhpStorm.
 * User: 3525339
 * Date: 12/10/2018
 * Time: 2:59 PM
 */
class IndexSort extends TInteger
{
    protected $_value = 1;    // 1 = ascending, -1 = descending, defaults to ascending

    public function set($in): void
    {
        $in = self::_castIfObject($in);
        $this->_value = ((int)self::_castIfObject($in)) > 0 ? 1 : -1;
    }

}
