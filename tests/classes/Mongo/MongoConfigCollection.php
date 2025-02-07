<?php

namespace TestClasses;

use Diskerror\Typed\TypedArray;

class MongoConfigCollection extends TypedArray
{
	protected $_type = MongoIndexDef::class;
}
