<?php

namespace Tweet\Entities;

use Diskerror\Typed\{TypedClass, TypedArray};

class UserMentions extends TypedClass
{
	protected $id          = '';

	protected $screen_name = '';

	protected $name        = '';

//	protected $indices     = '__class__Diskerror\Typed\TypedArray(null, "int")';
	protected $indices     = ['__type__' => TypedArray::class, null, 'int'];

}
