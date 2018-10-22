<?php

use Diskerror\Typed\TypedClass;

class SimpleTyped extends TypedClass
{
	protected $myBool       = true;
	protected $myInt        = 0;
	protected $myFloat      = 3.14;
	protected $myString     = '';
	protected $myArray      = [];
	protected $myObj        = '__class__stdClass';
//	protected $myTypedArray = '__class__Diskerror\Typed\TypedArray(null, "JRandomClass")';
	protected $myTypedArray = ['__type__' => 'Diskerror\Typed\TypedArray', null, 'JRandomClass'];
	protected $_map         = ['myDouble' => 'myFloat'];

}

class JRandomClass extends TypedClass
{
	protected $jRandomVar = '';
}
