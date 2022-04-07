<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class TwitterAuth extends TypedClass
{
	protected TString $consumer_key;
	protected TString $consumer_secret;
	protected TString $oauth_token;
	protected TString $oauth_token_secret;
}
