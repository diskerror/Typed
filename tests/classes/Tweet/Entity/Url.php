<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Url extends TypedClass
{
	protected string     $url          = '';    //	We could do some fancy filtering for this.
	protected string     $expanded_url = '';
	protected string     $display_url  = '';
	protected TypedArray $indices;

	protected function _initializeObjects()
	{
		$this->indices = new TypedArray('int');
	}

}
