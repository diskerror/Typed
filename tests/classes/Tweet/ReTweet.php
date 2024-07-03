<?php

namespace TestClasses\Tweet;

use Diskerror\Typed\TypedClass;

class ReTweet extends TypedClass
{
	public ?int $id = 0;

	use TweetTrait;

}
