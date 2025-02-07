<?php


namespace Diskerror\Typed\BSON;

use DateTimeZone;
use MongoDB\BSON\Persistable;
use MongoDB\BSON\UTCDateTime;

class DateTime extends \Diskerror\Typed\DateTime implements Persistable
{
	use DateTrait;

	/**
	 * @return UTCDateTime
	 */
	public function bsonSerialize(): UTCDateTime
	{
		$tzName = $this->getTimezone()->getName();
		if ($tzName === 'UTC' || substr($tzName, -5) === '00:00' || substr($tzName, -4) === '0000') {
			return new UTCDateTime($this);
		}
		$d = clone $this;
		$d->setTimezone(new DateTimeZone('UTC'));
		return new UTCDateTime($d);
	}

	/**
	 * @param array $data
	 * @return : void
	 */
	public function bsonUnserialize(array $data): void
	{
		$tz = $this->getTimezone();
		$this->setTimezone(new DateTimeZone('UTC'));
		$this->setTime(0, 0, 0, $data['milliseconds'] * 1000);
		$this->setTimezone($tz);
	}
}
