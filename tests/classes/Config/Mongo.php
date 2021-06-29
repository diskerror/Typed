<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class Mongo extends TypedClass
{
	protected $host     = [TString::class];
	protected $database = [TString::class];
}
