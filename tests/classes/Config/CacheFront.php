<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;

class CacheFront extends TypedClass
{
	protected ?TIntegerUnsigned $lifetime;
	protected string            $adapter = 'data';

}
