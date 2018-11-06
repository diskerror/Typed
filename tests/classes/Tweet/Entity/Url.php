<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Url extends TypedClass
{
	protected $url          = '';

	protected $expanded_url = '';

	protected $display_url  = '';

	protected $indices      = [TypedArray::class, 'int'];
}
