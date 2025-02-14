<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\BSON\{TypedArray, TypedClass};

class IndexDef extends TypedClass
{
    protected TypedArray $keys;
    protected TypedArray $options;

    protected function _initProperties(): void
    {
        $this->keys = new TypedArray(IndexSort::class);
    }

}
