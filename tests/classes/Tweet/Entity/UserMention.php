<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{TypedClass, TypedArray};

class UserMention extends TypedClass
{
	protected string     $id          = '';
	protected string     $screen_name = '';
	protected string     $name        = '';
	protected TypedArray $indices;

	protected function _initializeObjects()
	{
		$this->indices = new TypedArray('int');
	}
}
