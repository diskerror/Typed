<?php

namespace Diskerror\Typed;

use DateTimeZone;
use InvalidArgumentException;

/**
 * This class adds convienence methods to the built-in DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class DateTime extends \DateTime
{
	/**
	 * Default MySQL datetime format.
	 */
	const STRING_IO_FORMAT       = 'Y-m-d H:i:s';
	const STRING_IO_FORMAT_MICRO = 'Y-m-d H:i:s.u';

	/**
	 * Accepts a DateTime object or;
	 * Adds the ability to pass in an array or object with key names of variable
	 *       length but a minimum of 3 characters, upper or lower case.
	 * See setTime and setDate for more information.
	 *
	 * @param object|array|string $time     -OPTIONAL
	 * @param \DateTimeZone       $timezone -OPTIONAL
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($time = 'now', $timezone = null)
	{
		if (!is_a($timezone, 'DateTimeZone')) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}

		switch (gettype($time)) {
			case 'object':
				if (is_a($time, 'DateTime')) {
					parent::__construct($time->format(self::STRING_IO_FORMAT_MICRO), $time->getTimezone());
					break;
				}
				$time = (array)$time;
			case 'array':
				parent::__construct('now', $timezone);
				$this->setDate($time);
				$this->setTime($time);
			break;

			case 'string':
				if ($time === '') {
					$time = 'now';
				}
				//	remove AD extra data
				if (substr($time, -3) === '.0Z') {
					$time = substr($time, 0, 14);
				}
				parent::__construct($time, $timezone);
			break;

			case 'null':
			case 'NULL':
				parent::__construct('now', $timezone);
			break;

			default:
				throw new InvalidArgumentException('first argument is the wrong type: ' . gettype($time));
		}
	}

	/**
	 * Create DateTime object defaults to something that will accept
	 * the default MySQL datetime format of "Y-m-d H:i:s.u".
	 *
	 * @param string        $formatOrTime
	 * @param string        $time     -OPTIONAL
	 * @param \DateTimeZone $timezone -OPTIONAL
	 *
	 * @return DateTime
	 */
	public static function createFromFormat($formatOrTime, $time = '', $timezone = null): self
	{
		$parsed = ($time === '') ? date_parse($formatOrTime) : date_parse_from_format($formatOrTime, $time);

		$d = new self();

		if ($timezone !== null) {
			$d->setTimezone($timezone);
		}

		$d->setDate($parsed);
		$d->setTime($parsed);

		return $d;
	}

	/**
	 * Adds the ability to pass in an arrayor object with key names of variable
	 *      length but a minimum of 3 characters, upper or lower case.
	 * Requires one object, one associative array, or 3 integer parameters.
	 *
	 * Notice: The function "getdate()" returns an array with both
	 *      "month" and "mon" and will cause confusion here.
	 *
	 * @param object|array|int $year
	 * @param int              $month -DEFAULT 1
	 * @param int              $day   -DEFAULT 1
	 */
	public function setDate($year, $month = 1, $day = 1)
	{
		switch (gettype($year)) {
			case 'object':
				$year = (array)$year;

			case 'array':
				//	change all keys to lower case
				$arrIn = array_change_key_case($year);

				//	get current values as input can be incomplete
				$year  = $this->format('Y');
				$month = $this->format('n');
				$day   = $this->format('j');

				foreach ($arrIn as $k => $v) {
					switch (substr($k, 0, 3)) {
						case 'yea':
							$year = $v;
						break;

						case 'mon':
							$month = $v;
						break;

						case 'day':
							$day = $v;
						break;
					}
				}
			break;
		}

		parent::setDate((int)$year, (int)$month, (int)$day);

		return $this;
	}

	/**
	 * Adds the ability to pass in an array with key names of variable
	 *      length but a minimum of 3 characters, upper or lower case.
	 * Requires one object, one associative array, or 4 integer parameters.
	 *
	 * @param object|array|int $hour
	 * @param int              $minute
	 * @param int              $second
	 * @param int              $mcs Microseconds
	 */
	public function setTime($hour, $minute = 0, $second = 0, $mcs = 0)
	{
		switch (gettype($hour)) {
			case 'object':
				$hour = (array)$hour;

			case 'array':
				//	change all keys to lower case
				$arrIn = array_change_key_case($hour);

				//	get current values as input can be incomplete
				$hour   = $this->format('G');
				$minute = $this->format('i');
				$second = $this->format('s');
				$mcs    = $this->format('u');

				foreach ($arrIn as $k => $v) {
					switch (substr(strtolower($k), 0, 3)) {
						case 'hou':
							$hour = $v;
						break;

						case 'min':
							$minute = $v;
						break;

						case 'sec':
							$second = $v;
						break;

						case 'mcs':
							$mcs = $v;
						break;

						case 'fra': //	"fraction" which is a float
							$mcs = $v * 1000000;
						break;
					}
				}
		}

		parent::setTime((int)$hour, (int)$minute, (int)$second, (int)$mcs);

		return $this;
	}

	/**
	 * Returns MySQL default formatted date-time string.
	 * If a custom formatting is desired use DateTime::format($format).
	 *
	 * @return string
	 */
	public function __toString()
	{
		if ($this->format('u') > 0) {
			return $this->format(self::STRING_IO_FORMAT_MICRO);
		}

		return $this->format(self::STRING_IO_FORMAT);
	}

	/**
	 * Returns an integer, as the timestamp in milliseconds since the Unix epoch.
	 *
	 * @return int
	 */
	public function getTimestampMilli(): int
	{
		return ($this->getTimestamp() * 1000) + $this->format('v');
	}

	/**
	 * Returns a float, as the timestamp in seconds since the Unix epoch accurate to the nearest microsecond.
	 *
	 * @return float
	 */
	public function getTimestampMicro(): float
	{
		return (float)$this->getTimestamp() + ($this->format('u') / 1000000.0);
	}

}
