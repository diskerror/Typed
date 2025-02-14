<?php

namespace TestClasses;

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

class IndexDef extends TypedClass
{
	protected TypedArray $keys;
	protected TypedArray $options;

	protected function _initProperties(): void
	{
		$this->keys    = new TypedArray(IndexSort::class);
		$this->options = new TypedArray();
	}
}
