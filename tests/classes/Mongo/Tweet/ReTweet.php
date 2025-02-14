<?php

namespace TestClasses\Mongo\Tweet;

use Diskerror\Typed\BSON\TypedClass;

class ReTweet extends TypedClass
{
	public ?int $id = 0;

	use TweetTrait;

}
