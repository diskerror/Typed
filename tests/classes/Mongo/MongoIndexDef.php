<?php

namespace TestClasses;

use Diskerror\Typed\{TypedArray, TypedClass};

class MongoIndexDef extends TypedClass
{
	protected $keys    = [TypedArray::class, MongoIndexSort::class];
	protected $options = [TypedArray::class];
}
