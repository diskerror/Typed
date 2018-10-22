<?php

namespace Tweet\Entities;

use Diskerror\Typed\{TypedClass, TypedArray};

class Entities extends TypedClass
{
//	protected $hashtags      = '__class__\Diskerror\Typed\TypedArray(null, "\Tweet\Entities\Hashtags")';
	protected $hashtags      = ['__type__' => TypedArray::class, null, 'Tweet\Entities\Hashtags'];
	protected $urls          = '__class__\Diskerror\Typed\TypedArray(null, "\Tweet\Entities\Urls")';
	protected $user_mentions = '__class__\Diskerror\Typed\TypedArray(null, "\Tweet\Entities\UserMentions")';

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
