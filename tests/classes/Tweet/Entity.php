<?php

namespace TestClasses\Tweet;

use TestClasses\Tweet\Entity\{Hashtag, Url, UserMention};
use Diskerror\Typed\{TypedClass, TypedArray};

class Entity extends TypedClass
{
	protected TypedArray $hashtags;
	protected TypedArray $urls;
	protected TypedArray $user_mentions;

	protected function _initializeObjects()
	{
		$this->hashtags      = new TypedArray(Hashtag::class);
		$this->urls          = new TypedArray(Url::class);
		$this->user_mentions = new TypedArray(UserMention::class);
	}

}
