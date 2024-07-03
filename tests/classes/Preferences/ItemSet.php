<?php

namespace TestClasses\Preferences;

use Diskerror\Typed\TypedClass;

class ItemSet extends TypedClass
{
	protected Item $name;
	protected Item $address;
	protected Item $city;
	protected Item $state;
	protected Item $zip;

}
