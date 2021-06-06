<?php

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class IndexDef extends TypedClass
{
	protected $keys    = [TypedArray::class, IndexSort::class];
	protected $options = [TypedArray::class];
}
