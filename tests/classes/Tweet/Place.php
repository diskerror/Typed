<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\TypedClass;

class Place extends TypedClass
{
	protected int    $id           = 0;
	protected string $url          = '';
	protected string $place_type   = '';
	protected string $name         = '';
	protected string $full_name    = '';
	protected string $country_code = '';
	protected string $country      = '';
}
