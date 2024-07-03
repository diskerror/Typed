<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\{TypedArray, TypedClass};
use TestClasses\Tweet\Entity\{Hashtag, Url, UserMention};

/**
 * @property TypedArray $hashtags
 * @property TypedArray $urls
 * @property TypedArray $user_mentions
 */
class Entity extends TypedClass
{
	protected TypedArray $hashtags;
	protected TypedArray $urls;
	protected TypedArray $user_mentions;

	public function __construct($in = null)
	{
		$this->hashtags      = new TypedArray(Hashtag::class);
		$this->urls          = new TypedArray(Url::class);
		$this->user_mentions = new TypedArray(UserMention::class);

		parent::__construct($in);
	}

}
