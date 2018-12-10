<?php

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class MongoIndexDef extends TypedClass
{
	protected $keys    = [TypedArray::class, MongoIndexSort::class];
	protected $options = [TypedArray::class];
}
