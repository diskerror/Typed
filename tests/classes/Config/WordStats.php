<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

class WordStats extends TypedClass
{
	protected TIntegerUnsigned $quantity;
	protected TIntegerUnsigned $window;
	protected WordList $stop;
}
