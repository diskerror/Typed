<?php

namespace Tweet\Entity;

use Diskerror\Typed\{TypedClass, TypedArray};

class Entity extends TypedClass
{
	protected $hashtags      = [TypedArray::class, null, Hashtag::class];
	protected $urls          = [TypedArray::class, null, Url::class];
	protected $user_mentions = [TypedArray::class, null, UserMention::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
