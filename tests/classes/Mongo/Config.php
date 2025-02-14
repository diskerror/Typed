<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\BSON\TypedArray;
use Diskerror\Typed\BSON\TypedClass;

/**
 * Class Config
 *
 * @package TestClasses\Mongo
 *
 * @property string     $host
 * @property string     $database
 * @property Collection $collections
 */
class Config extends TypedClass
{
    protected string     $host     = 'mongodb://localhost';
    protected string     $database = '';
    protected TypedArray $collections;

    protected function _initProperties(): void
    {
        $this->collections = new TypedArray(Collection::class);
    }
}
