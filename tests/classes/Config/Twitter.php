<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Twitter extends TypedClass
{
	protected TwitterAuth $auth;
	protected WordList    $track;
}
