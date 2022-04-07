<?php

namespace TestClasses;

use TestClasses\Config\{Caches, Mongo, Process, Twitter, WordStats};
use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class Config extends TypedClass
{
	protected $version       = [TString::class];
	protected $mongo_db      = [Mongo::class];
	protected $tweets_expire = 600;
	protected $word_stats    = [WordStats::class];
	protected $twitter       = [Twitter::class];
	protected $process       = [Process::class];
	protected $caches        = [Caches::class];
}
