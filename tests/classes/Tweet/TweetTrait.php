<?php

namespace TestClasses\Tweet;

use DateTimeZone;
use Diskerror\Typed\DateTime;
use Diskerror\Typed\Scalar\TBoolean;
use Diskerror\Typed\Scalar\TInteger;
use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\Scalar\TStringNormalize;

/**
 * @property DateTime $created_at
 * @property string $contributors
 * @property Entity $entities
 * @property ExtendedEntities $extended_entities;
 * @property int $favorite_count
 * @property string $filter_level
 * @property string $in_reply_to_screen_name
 * @property string $in_reply_to_status_id_str
 * @property string $in_reply_to_user_id_str
 * @property bool $is_quote_status
 * @property string $lang
 * @property Place $place
 * @property bool $possibly_sensitive
 * @property int $retweet_count
 * @property string $source
 * @property TStringNormalize $text
 * @property bool $truncated
 * @property User $user
 */
trait TweetTrait
{

	protected DateTime         $created_at;
	protected TString          $contributors;
	protected Entity           $entities;
	protected ExtendedEntities $extended_entities;
	protected TInteger         $favorite_count;
	protected TString          $filter_level;
	protected TString          $in_reply_to_screen_name;
	protected TString          $in_reply_to_status_id_str;
	protected TString          $in_reply_to_user_id_str;
	protected TBoolean         $is_quote_status;
	protected TString          $lang;
	protected Place            $place;
	protected TBoolean         $possibly_sensitive;
	protected TInteger         $retweet_count;
	protected TString          $source;    //	address html tag
	protected TStringNormalize $text;
	protected TBoolean         $truncated;
	protected User             $user;

	public function __construct($in = null)
	{
		$this->created_at   = new DateTime('2018-07-18 17:10:28', new DateTimeZone('UTC'));
		$this->filter_level = new TString('low');
		$this->lang         = new TString('en');

		parent::__construct($in);
	}

}
