<?php

use Diskerror\Typed\{TypedClass, TypedArray};

/**
 * Class SimpleTyped
 *
 * @property $myBool
 * @property $myInt
 * @property $myFloat
 * @property $myString
 * @property $myArray
 * @property $myObj
 * @property $myTypedArray
 */
class SimpleTyped extends TypedClass
{
	protected $myBool       = true;
	protected $myInt        = 0;
	protected $myFloat      = 3.14;
	protected $myString     = '';
	protected $myArray      = [];
	protected $myObj        = ['__type__' => 'stdClass'];
	protected $myTypedArray = ['__type__' => TypedArray::class, null, JRandomClass::class];
	protected $_map         = ['myDouble' => 'myFloat'];

}

class JRandomClass extends TypedClass
{
	protected $jRandomVar = '';
}
