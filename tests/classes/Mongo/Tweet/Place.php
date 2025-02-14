<?php

namespace TestClasses\Mongo\Tweet;

use Diskerror\Typed\BSON\TypedClass;
use Diskerror\Typed\Scalar\{TIntegerUnsigned, TString};

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
