<?php

namespace Tweet;

use Diskerror\Typed\DateTime;
use Diskerror\Typed\TypedClass;

class User extends TypedClass
{
	protected $id                   = 0;

	protected $name                 = '';

	protected $screen_name          = '';

	protected $location             = '';

	protected $contributors_enabled = false;

	protected $created_at           = [DateTime::class, '2018-07-18 17:10:28'];

	protected $description          = '';

	protected $favourites_count     = 0;

	protected $followers_count      = 0;

	protected $friends_count        = 0;

	protected $geo_enabled          = false;

	protected $is_translator        = false;

	protected $lang                 = 'en';

	protected $listed_count         = 0;

	protected $protected            = false;

	protected $statuses_count       = 0;

	protected $time_zone            = '';

	protected $url                  = '';

	protected $verified             = false;

	protected function _set_description($v)
	{
		$this->description = preg_replace('/\s+/', ' ', \Normalizer::normalize((string)$v, \Normalizer::FORM_D));
	}

}
