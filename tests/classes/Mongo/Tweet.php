<?php

namespace TestClasses\Mongo;

use Diskerror\Typed\BSON\TypedClass;
use TestClasses\Mongo\Tweet\ReTweet;
use TestClasses\Mongo\Tweet\TweetTrait;

/**
 * Class Tweet
 */
class Tweet extends TypedClass
{
	protected array $_map = [
		'id' => '_id',    // 'id' received from Twitter
	];

	public int $_id = 0;

	use TweetTrait;

	protected ReTweet $retweeted_status;
}
