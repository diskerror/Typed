<?php

namespace TestClasses;

use Diskerror\Typed\DateTime as MyDateTime;
use Diskerror\Typed\Scalar\TFloat;
use Diskerror\Typed\Scalar\TInteger;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use stdClass;


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
	protected array $_map = ['myDouble' => 'myFloat'];

	protected bool       $myBool   = true;
	protected TInteger   $myInt;
	protected TFloat     $myFloat;
	protected string     $myString = '';
	protected TypedArray $myArray;
	protected stdClass   $myObj;
	protected MyDateTime $myDate;
	protected TypedArray $myTypedArray;

	protected function _initializeObjects()
	{
		$this->myInt        = new TInteger(0);
		$this->myFloat      = new TFloat(3.14);
		$this->myArray      = new TypedArray();
		$this->myObj        = new stdClass();
		$this->myDate       = new MyDateTime('2010-01-01T01:01:01.001+00:00');
		$this->myTypedArray = new TypedArray(JRandom::class);
	}


}

class JRandom extends TypedClass
{
	protected $jRandomVar = '';
}
