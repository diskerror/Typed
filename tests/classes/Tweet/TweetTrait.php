<?php

namespace Tweet;

use Diskerror\Typed\DateTime;

trait TweetTrait {

	protected $created_at                = [DateTime::class, '2018-07-18 17:10:28'];

	protected $contributors              = null;

	protected $entities                  = [Entity\Entity::class];

	protected $extended_entities         = [ExtendedEntities::class];

	protected $favorite_count            = 0;

	protected $filter_level              = 'low';

	protected $in_reply_to_screen_name   = '';

	protected $in_reply_to_status_id_str = '';

	protected $in_reply_to_user_id_str   = '';

	protected $is_quote_status           = false;

	protected $lang                      = 'en';

	protected $place                     = [Place::class];

	protected $possibly_sensitive        = false;

	protected $retweet_count             = 0;

	protected $source                    = '';

	protected $text                      = '';

	protected $truncated                 = false;

	protected $user                      = [User::class];


	protected function _set_text($v)
	{
		$this->text = preg_replace('/\s+/', ' ', \Normalizer::normalize((string)$v, \Normalizer::FORM_D));
	}

}
