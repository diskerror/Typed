<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedClass, TypedArray};

class Entity extends TypedClass
{
	protected $hashtags      = ['__type__' => TypedArray::class, null, Hashtag::class];
	protected $urls          = ['__type__' => TypedArray::class, null, Url::class];
	protected $user_mentions = ['__type__' => TypedArray::class, null, UserMention::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
