<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

class CacheFront extends TypedClass
{
	protected $lifetime = [TIntegerUnsigned::class];
	protected $adapter  = 'data';
}
