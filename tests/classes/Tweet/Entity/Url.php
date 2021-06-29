<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Url extends TypedClass
{
	protected $url          = '';	//	We could do some fancy filtering for this.

	protected $expanded_url = '';

	protected $display_url  = '';

	protected $indices      = [TypedArray::class, 'int'];
}
