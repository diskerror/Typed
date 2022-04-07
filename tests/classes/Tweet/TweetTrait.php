<?php

namespace TestClasses\Tweet;

use DateTimeZone;
use Diskerror\Typed\DateTime;
use Diskerror\Typed\Scalar\TStringNormalize;

trait TweetTrait
{

	protected DateTime         $created_at;
	protected string          $contributors              = '';
	protected Entity           $entities;
	protected ExtendedEntities $extended_entities;
	protected int              $favorite_count            = 0;
	protected string           $filter_level              = 'low';
	protected string           $in_reply_to_screen_name   = '';
	protected string           $in_reply_to_status_id_str = '';
	protected string           $in_reply_to_user_id_str   = '';
	protected bool             $is_quote_status           = false;
	protected string           $lang                      = 'en';
	protected Place            $place;
	protected bool             $possibly_sensitive        = false;
	protected int              $retweet_count             = 0;
	protected string           $source                    = '';    //	address html tag
	protected TStringNormalize $text;
	protected bool             $truncated                 = false;
	protected User             $user;


	protected function _initializeObjects()
	{
		$this->created_at = new DateTime('2018-07-18 17:10:28', new DateTimeZone('UTC'));
	}

}
