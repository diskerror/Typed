<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\BSON\TypedArray;

/**
 * Class Collection is an array of MongoDB collection index definitions.
 *
 */
class Collection extends TypedArray
{
	protected string $_type = IndexDef::class;
}
