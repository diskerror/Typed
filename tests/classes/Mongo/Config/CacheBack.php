<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class CacheBack extends TypedClass
{
	protected $directory = '';
	protected $prefix    = '';
	protected $frontend  = null;
	protected $adapter   = '';
}
