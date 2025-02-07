<?php

namespace Diskerror\Typed\BSON;

use DateTimeZone;
use MongoDB\BSON\UTCDateTimeInterface;

/**
 *	Methods to be added to both DateTime and Date.
 */
trait DateTrait
{
	public function __construct($time = 'now', $timezone = null)
	{
		if ($timezone === null) {
			$timezone = new DateTimeZone('UTC');
		}

		$this->_initCheckBson($time);
		parent::__construct($time, $timezone);
	}

	/**
	 * Adds the ability to accept a Mongo\BSON\UTCDateTime object.
	 *
	 * @param mixed $time
	 */
	public function _initCheckBson(&$time)
	{
		if (is_object($time) && $time instanceof UTCDateTimeInterface) {
			$time = $time->toDateTime();
		}
	}
}
