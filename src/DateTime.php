<?php

namespace Diskerror\Typed;

use DateTime as DT;
use DateTimeZone;
use InvalidArgumentException;
use JsonSerializable;

/**
 * This class adds convenience methods to the built-in DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class DateTime extends DT implements JsonSerializable
{
	/**
	 * Default MySQL datetime format.
	 */
	public const STRING_IO_FORMAT       = 'Y-m-d H:i:s';
	public const STRING_IO_FORMAT_MICRO = 'Y-m-d H:i:s.u';
	public const STRING_IO_FORMAT_JSON  = 'Y-m-d\TH:i:s.vP';

	/**
	 * Accepts a DateTime object or;
	 * Adds the ability to pass in an array or object with key names of variable
	 *       length but a minimum of 3 characters, upper or lower case.
	 * See setTime and setDate for more information.
	 *
	 * @param mixed        $time     -OPTIONAL
	 * @param DateTimeZone $timezone -OPTIONAL
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct($time = 'now', $timezone = null)
	{
		if (!($timezone instanceof DateTimeZone)) {
			$timezone = new DateTimeZone(date_default_timezone_get());
		}

		switch (gettype($time)) {
			case 'object':
				if ($time instanceof DateTimeInterface) {
					$this->date          = $time->date;
					$this->timezone_type = $time->timezone_type;
					$this->timezone      = $time->timezone;
					break;
				}
			//	no break, fall through if not instance of DateTimeInterface
			case 'array':
				parent::__construct('now', $timezone);
				$this->setDate($time);
				$this->setTime($time);
				break;

			case 'string':
				if ($time === '') {
					parent::__construct('now', $timezone);
				}
				//	remove AD extra data
				elseif (substr($time, -3) === '.0Z') {
					parent::__construct(substr($time, 0, -3), $timezone);
				}
				elseif ($time[0] === '@' && strpos($time, '.')) {
					//	if this contains fractional seconds
					//	$tmp is a PHP \DateTime
					$tmp = DT::createFromFormat('U.u', sprintf('%f', substr($time, 1)), $timezone);

					$this->date          = $tmp->date;
					$this->timezone_type = $tmp->timezone_type;
					$this->timezone      = $tmp->timezone;
				}
				else {
					parent::__construct($time, $timezone);
				}
				break;

			case 'int':
			case 'integer':
				parent::__construct('@' . $time, $timezone);
				break;

			case 'float':
			case 'double':
				$tmp = DT::createFromFormat('U.u', sprintf('%f', $time), $timezone);

				$this->date          = $tmp->date;
				$this->timezone_type = $tmp->timezone_type;
				$this->timezone      = $tmp->timezone;
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
	 * Adds the ability to pass in an array or object with key names of variable
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
			case 'array':
				$arrIn = $year;

				//	get current values as input can be incomplete
				$year  = $this->format('Y');
				$month = $this->format('n');
				$day   = $this->format('j');

				foreach ($arrIn as $k => $v) {
					switch (substr(strtolower($k), 0, 3)) {
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

		parent::setDate((int) $year, (int) $month, (int) $day);

		return $this;
	}

	/**
	 * Adds the ability to pass in an array with key names of variable
	 *      length but a minimum of 3 characters, upper or lower case.
	 *        Only the matched value is updated when using an array or object.
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
			case 'array':
				$arrIn = $hour;
				//	get current values as input is allowed to be incomplete
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

						case 'fra': //	Convert "fraction", a float, to microseconds as an integer
							$mcs = $v * 1000000;
							break;
					}
				}
		}

		parent::setTime((int) $hour, (int) $minute, (int) $second, (int) $mcs);

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
			return rtrim($this->format(self::STRING_IO_FORMAT_MICRO), '0');    //	also remove trailing zeros
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
		return ($this->format('U.v') * 1000);
	}

	/**
	 * Returns a float, as the timestamp in seconds since the Unix epoch accurate to the nearest microsecond.
	 *
	 * @return float
	 */
	public function getTimestampMicro(): float
	{
		return (float) $this->format('U.u');
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string
	{
		return $this->format(self::RFC3339_EXTENDED);
	}
}
