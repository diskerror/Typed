<?php

namespace TestClasses;

use Diskerror\TypedBSON\DateTime as MyDateTime;
use Diskerror\TypedBSON\{TypedArray,TypedClass};


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
	protected $myObj        = ['stdClass'];
	protected $myDate    	= [MyDateTime::class, "2010-01-01T01:01:01.001+00:00"];
	protected $myTypedArray = [TypedArray::class, JRandom::class];
	protected $_map         = ['myDouble' => 'myFloat'];

}

class JRandom extends TypedClass
{
	protected $jRandomVar = '';
}
