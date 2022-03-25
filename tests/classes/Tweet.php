<?php

namespace TestClasses;

use Diskerror\Typed\TypedClass;
use TestClasses\Tweet\ReTweet;
use TestClasses\Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected array $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected $_id  = 0;

	use TweetTrait;

	protected $retweeted_status = [ReTweet::class];

}
