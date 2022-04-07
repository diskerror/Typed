<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Cache extends TypedClass
{
	protected CacheFront $front;
	protected CacheBack  $back;
}
