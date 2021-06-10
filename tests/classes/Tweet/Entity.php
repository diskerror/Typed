<?php

namespace Tweet;

use Diskerror\Typed\{TypedClass, TypedArray};
use Tweet\Entity\Hashtag;
use Tweet\Entity\Url;
use Tweet\Entity\UserMention;

class Entity extends TypedClass
{
	protected $hashtags      = [TypedArray::class, Hashtag::class];
	protected $urls          = [TypedArray::class, Url::class];
	protected $user_mentions = [TypedArray::class, UserMention
	::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
