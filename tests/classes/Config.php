<?php

namespace TestClasses;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;
use TestClasses\Config\{Caches, Mongo, Process, Twitter, WordStats};

class Config extends TypedClass
{
	protected TString   $version;
	protected Mongo     $mongo_db;
	public int          $tweets_expire = 600;
	protected WordStats $word_stats;
	protected Twitter   $twitter;
	protected Process   $process;
	protected Caches    $caches;
}
