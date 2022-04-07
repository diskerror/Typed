<?php

namespace TestClasses;

use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\TypedClass;
use TestClasses\Tweet\ReTweet;
use TestClasses\Tweet\TweetTrait;

class Tweet extends TypedClass
{
	protected array $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected TIntegerUnsigned $_id;

	use TweetTrait;

	protected ReTweet $retweeted_status;

	protected function _initializeObjects()
	{
		$this->_id = new TIntegerUnsigned();
	}


}
