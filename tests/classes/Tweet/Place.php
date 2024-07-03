<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class Place extends TypedClass
{
	protected TIntegerUnsigned $id;
	protected TString          $url;
	protected TString          $place_type;
	protected TString          $name;
	protected TString          $full_name;
	protected TString          $country_code;
	protected TString          $country;
}
