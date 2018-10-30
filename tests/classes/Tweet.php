<?php

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends Tweet\TweetBase
{
	protected $retweeted_status = ['__type__' => \Tweet\TweetBase::class];

	public function __construct($in = null)
	{
		parent::__construct($in);

		//	This is only here as an example.
		$this->_toBsonOptions = new AO(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE | AO::NO_CAST_BSON_ID);

		//	We can only test JSON option here.
		$this->_toJsonOptions = new AO(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE);
	}

}
