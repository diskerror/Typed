<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\TypedClass;

class ReTweet extends TypedClass
{
	protected int $id = 0;

	use TweetTrait;

}
