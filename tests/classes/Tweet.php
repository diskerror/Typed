<?php

require 'Tweet/TweetTrait.php';

use Diskerror\Typed\TypedClass;
use Tweet\ReTweet;
use Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected $_id  = 0;

	use TweetTrait;

	protected $retweeted_status = [ReTweet::class];

}
