<?php

namespace TestClasses\Tweet;

use DateTimeZone;
use Diskerror\Typed\DateTime;
use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\Typed\TypedClass;

class User extends TypedClass
{
	protected int              $id                   = 0;
	protected string           $name                 = '';
	protected string           $screen_name          = '';
	protected string           $location             = '';
	protected bool             $contributors_enabled = false;
	protected DateTime         $created_at;
	protected TStringNormalize $description;
	protected int              $favourites_count     = 0;
	protected int              $followers_count      = 0;
	protected int              $friends_count        = 0;
	protected bool             $geo_enabled          = false;
	protected bool             $is_translator        = false;
	protected string           $lang                 = 'en';
	protected int              $listed_count         = 0;
	protected bool             $protected            = false;
	protected int              $statuses_count       = 0;
	protected string           $time_zone            = '';
	protected string           $url                  = '';
	protected bool             $verified             = false;

	protected function _initializeObjects()
	{
		$this->created_at = new DateTime('2018-07-18 17:10:28', new DateTimeZone('UTC'));
	}

}
