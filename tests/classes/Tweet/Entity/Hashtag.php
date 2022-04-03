<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{TypedArray, TypedClass};

class Hashtag extends TypedClass
{
	protected string     $text = '';
	protected TypedArray $indices;

	protected function _initializeObjects()
	{
		$this->indices = new TypedArray('int');
	}

}
