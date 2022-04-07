<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Process extends TypedClass
{
	protected string $name    = '';
	protected string $path    = '';
	protected string $procDir = '';
}
