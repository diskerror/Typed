<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Hashtag extends TypedClass
{
	protected $text    = '';

	protected $indices = ['__type__' => TypedArray::class, null, 'int'];
}
