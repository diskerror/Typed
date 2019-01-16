<?php

//use Diskerror\Typed\ArrayOptions as AO;

use Diskerror\Typed\TypedClass;
use Tweet\TweetBase;

class Tweet extends TypedClass
{
	protected $_map = [
		'id' => '_id',    //	from Twitter
	];

	protected $_id  = 0;

	use TweetBase;

//	//	This is only here as an example.
//	protected $_bsonOptionDefaults = AO::OMIT_RESOURCE | AO::TO_BSON_DATE | AO::CAST_BSON_ID;
//
//	//	We can only test JSON option here.
//	protected $_jsonOptionDefaults = AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE;
//
	protected $retweeted_status = [\Tweet\TweetBase::class];

}
