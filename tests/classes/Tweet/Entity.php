<?php

namespace TestClasses\Tweet;

use TestClasses\Tweet\Entity\Hashtag;
use TestClasses\Tweet\Entity\Url;
use TestClasses\Tweet\Entity\UserMention;
use Diskerror\Typed\{TypedClass, TypedArray};

class Entity extends TypedClass
{
	protected $hashtags      = [TypedArray::class, Hashtag::class];
	protected $urls          = [TypedArray::class, Url::class];
	protected $user_mentions = [TypedArray::class, UserMention::class];

// 	protected $symbols  = '';
// 	protected $polls  = '';
}
