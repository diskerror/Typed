<?php

namespace TestClasses\Mongo;

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
class Config extends TypedClass
{
	protected $host        = 'mongodb://localhost';
	protected $database    = '';
	protected $collections = [TypedArray::class, ConfigCollection::class];
}
