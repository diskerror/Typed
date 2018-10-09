<?php

namespace Tweet\Entities;

class UserMentions extends \Diskerror\Typed\TypedClass
{
	protected $id          = '';

	protected $screen_name = '';

	protected $name        = '';

//	protected $indices     = '__class__Diskerror\Typed\TypedArray(null, "int")';
	protected $indices     = ['__type__' => 'Diskerror\Typed\TypedArray', null, 'int'];

}
