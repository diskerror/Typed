<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class Mongo extends TypedClass
{
	protected TString $host;
	protected TString $database;
}
