<?php

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;

/**
 * Class MongoConfig
 *
 * @package Structure\Config
 *
 * @property $host
 * @property $database
 * @property $collections
 */
class MongoConfig extends TypedClass
{
	protected $host        = 'mongodb://localhost';
	protected $database    = '';
	protected $collections = [TypedArray::class, MongoConfigCollection::class];
}
