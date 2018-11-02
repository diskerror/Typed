<?php

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends Tweet\TweetBase
{
	//	This is only here as an example.
	protected $_toBsonOptionDefaults = AO::OMIT_RESOURCE | AO::TO_BSON_DATE | AO::NO_CAST_BSON_ID;

	//	We can only test JSON option here.
	protected $_toJsonOptionDefaults = AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE;

	protected $retweeted_status      = ['__type__' => \Tweet\TweetBase::class];

}
