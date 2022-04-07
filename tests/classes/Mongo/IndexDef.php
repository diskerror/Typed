<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class IndexDef extends TypedClass
{
	protected TypedArray $keys;
	protected TypedArray $options;

	protected function _initializeObjects()
	{
		$this->keys    = new TypedArray(IndexSort::class);
		$this->options = new TypedArray();
	}
}
