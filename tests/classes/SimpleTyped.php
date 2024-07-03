<?php

namespace TestClasses;

use Diskerror\Typed\DateTime as MyDateTime;
use Diskerror\Typed\Scalar\TBoolean;
use Diskerror\Typed\Scalar\TFloat;
use Diskerror\Typed\Scalar\TInteger;
use Diskerror\Typed\Scalar\TString;
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

	protected TBoolean   $myBool;
	protected TInteger   $myInt;
	protected TFloat     $myFloat;
	protected TString    $myString;
	protected TypedArray $myArray;
	protected stdClass   $myObj;
	protected MyDateTime $myDate;
	protected TypedArray $myTypedArray;

	public function __construct($in = null)
	{
		$this->myBool       = new TBoolean(true, true);
		$this->myInt        = new TInteger(0);
		$this->myFloat      = new TFloat(3.14);
		$this->myString     = new TString();
		$this->myArray      = new TypedArray();
		$this->myObj        = new stdClass();
		$this->myDate       = new MyDateTime('2010-01-01T01:01:01.001+00:00');
		$this->myTypedArray = new TypedArray(JRandom::class);

		parent::__construct($in);
	}
}

class JRandom extends TypedClass
{
	protected $jRandomVar = 0;
}
