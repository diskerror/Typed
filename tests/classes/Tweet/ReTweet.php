<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\TypedClass;

class ReTweet extends TypedClass
{
	protected $id = 0;

	use TweetTrait;

}
