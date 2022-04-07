<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class CacheBack extends TypedClass
{
	protected string  $directory = '';
	protected string  $prefix    = '';
	protected ?string $frontend  = null;
	protected string  $adapter   = '';
}
