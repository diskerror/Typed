<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\TypedClass;

class Place extends TypedClass
{
	protected $id           = 0;

	protected $url          = '';

	protected $place_type   = '';

	protected $name         = '';

	protected $full_name    = '';

	protected $country_code = '';

	protected $country      = '';
}
