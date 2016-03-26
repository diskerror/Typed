<?php

class SimpleTyped extends \Diskerror\Typed\TypedClass
{
	protected $myBool = true;
	protected $myInt = 0;
	protected $myFloat = 3.14;
	protected $myString = '';
	protected $myArray = [];
	protected $myObj = '__class__stdClass';

	protected $_map = ['myDouble' => 'myFloat'];

}
