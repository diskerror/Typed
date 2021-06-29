<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Twitter extends TypedClass
{
	protected $auth  = [TwitterAuth::class];
	protected $track = [WordList::class];
}
