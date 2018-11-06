<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Hashtag extends TypedClass
{
	protected $text    = '';

	protected $indices = [TypedArray::class, 'int'];
}
