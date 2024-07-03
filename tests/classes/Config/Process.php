<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Process extends TypedClass
{
	public string $name    = '';
	public string $path    = '';
	public string $procDir = '';
}
