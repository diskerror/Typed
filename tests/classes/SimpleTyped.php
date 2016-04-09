<?php

class SimpleTyped extends \Diskerror\Typed\TypedClass
{
	protected $myBool = true;
	protected $myInt = 0;
	protected $myFloat = 3.14;
	protected $myString = '';
	protected $myArray = [];
	protected $myObj = '__class__stdClass';

	protected $myTypedArray = '__class__Diskerror\Typed\TypedArray(null, "JRandomClass")';

	protected $_map = ['myDouble' => 'myFloat'];

}

class JRandomClass extends \Diskerror\Typed\TypedClass
{
	protected $jRandomVar = '';
}