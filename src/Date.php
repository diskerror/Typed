<?php

namespace Diskerror\Typed;

use DateInterval;
use InvalidArgumentException;
use LogicException;

/**
 * This class adds convenience methods for date-only to Diskerror\DateTime.
 *
 * Date and time can be passed in with objects or associative arrays.
 *
 * THIS HAS NOT BEEN EXHAUSTIVELY TESTED. Particularly "add()" and "sub()".
 *
 * @copyright     Copyright (c) 2011 Reid Woodbury Jr.
 * @license       http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */
class Date extends DateTime
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
	 * @throws InvalidArgumentException
	 */
	public function __construct($time = 'now', $timezone = null)
	{
		parent::__construct($time, $timezone);
		parent::setTime(12, 0, 0);
	}

	/**
	 * Adds DateInterval to stored date and
	 *       sets time to noon to avoid possible Daylight Savings transition issues.
	 *
	 * @param DateInterval $interval
	 *
	 * @return Date
	 */
	public function add($interval)
	{
		parent::add($interval);
		parent::setTime(12, 0, 0);

		return $this;
	}

	/**
	 * Subtracts DateInterval from stored date and
	 *       sets time to noon to avoid possible Daylight Savings transition issues.
	 *
	 * @param DateInterval $interval
	 *
	 * @return Date
	 */
	public function sub($interval)
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
	 * @throws LogicException
	 */
	public function setTime($hou, $min = 0, $sec = 0, $mic = 0)
	{
		throw new LogicException('method not available in Date class');
	}

	/**
	 * @return string
	 */
	public function jsonSerialize(): string
	{
		return $this->format('Y-m-d');
	}
}
