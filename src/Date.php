<?php

namespace Diskerror\Typed;

/**
 * This class adds convienence methods for date-only to Diskerror\DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * THIS HAS NOT BEEN EXHAUSTIVELY TESTED. Particularly "add()" and "sub()".
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class Date extends \Diskerror\Typed\DateTime
{
	/**
	 * Adds date-only handling to DateTime object.
	 * Adds the ability to pass in an array with key names of variable
	 *       length but a minimum of 3 characters, upper or lower case.
	 * Sets time to noon to avoid possible Daylight Savings transition issues.
	 *
	 * @param object|array|string $time     -OPTIONAL
	 * @param string              $timezone -OPTIONAL
	 *
	 * @throws \InvalidArgumentException
	 */
	public function __construct($time = 'now', $timezone = null)
	{
		$timezone = (null === $timezone ? new \DateTimeZone(date_default_timezone_get()) : $timezone);

		switch (gettype($time)) {
			case 'object':
				if (is_a($time, 'DateTime')) {
					parent::__construct($time->format('Y-m-d 12:00:00'), $time->getTimezone());

					return;
				}
			case 'null':
			case 'NULL':
			case 'array':
				parent::__construct('now', $timezone);
				parent::setDate($time);
			break;

			case 'string':
				parent::__construct($time, $timezone);
			break;

			default:
				throw new \InvalidArgumentException('first argument is the wrong type');
		}

		parent::setTime(12, 0, 0);
	}

	/**
	 * Adds DateInterval to stored date and
	 *       sets time to 6am to avoid possible Daylight Savings transition issues.
	 *
	 * @param \DateInterval $interval
	 *
	 * @return Date
	 */
	public function add(\DateInterval $interval)
	{
		parent::add($interval);
		parent::setTime(12, 0, 0);

		return $this;
	}

	/**
	 * Subtracts DateInterval from stored date and
	 *       sets time to 6am to avoid possible Daylight Savings transition issues.
	 *
	 * @param \DateInterval $interval
	 *
	 * @return Date
	 */
	public function sub(\DateInterval $interval)
	{
		parent::sub($interval);
		parent::setTime(12, 0, 0);

		return $this;
	}

	/**
	 * Returns string suitable for default MySQL date format.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->format('Y-m-d');
	}

	/**
	 * Method shouldn't be used for Date object.
	 *
	 * @param array|int $hou
	 * @param int       $min
	 * @param int       $sec
	 * @param int       $mic
	 *
	 * @throws \LogicException
	 */
	public function setTime($hou, $min = 0, $sec = 0, $mic = 0)
	{
		throw new \LogicException('method not available in Date class');
	}
}
