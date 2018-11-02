<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedClass, TypedArray};

class UserMention extends TypedClass
{
	protected $id          = '';

	protected $screen_name = '';

	protected $name        = '';

	protected $indices     = ['__type__' => TypedArray::class, null, 'int'];

}