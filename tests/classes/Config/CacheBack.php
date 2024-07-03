<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class CacheBack extends TypedClass
{
	public string  $directory = '';
	public string  $prefix    = '';
	public ?string $frontend  = null;
	public string  $adapter   = '';
}
