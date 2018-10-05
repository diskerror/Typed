<?php

use Diskerror\Typed\ArrayOptions as AO;

class Tweet extends Tweet\TweetBase
{
	protected $retweeted_status = '__class__Tweet\TweetBase';

	public function __construct($in = null)
	{
		parent::__construct($in);
		$this->setArrayOptions(AO::OMIT_RESOURCE | AO::KEEP_JSON_EXPR);
	}
}
