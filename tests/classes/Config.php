<?php

namespace TestClasses;

use TestClasses\Config\Caches;
use TestClasses\Config\Mongo;
use TestClasses\Config\Process;
use TestClasses\Config\Twitter;
use TestClasses\Config\WordStats;
use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;

class Config extends TypedClass
{
	protected TString   $version;
	protected Mongo     $mongo_db;
	protected int       $tweets_expire = 600;
	protected WordStats $word_stats;
	protected Twitter   $twitter;
	protected Process   $process;
	protected Caches    $caches;

	protected function _initializeObjects()
	{
		$this->version    = new TString();
		$this->mongo_db   = new Mongo();
		$this->word_stats = new WordStats();
		$this->twitter    = new Twitter();
		$this->process    = new Process();
		$this->caches     = new Caches();
	}
}
