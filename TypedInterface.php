<?php

namespace Typed;

use Countable;

/**
 * Provides common interface for TypedAbstract and TypedArray.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
interface TypedInterface extends Countable
{
	/*
	 * Required method for Countable.
	 * @return int
	 */
	public function count();


	/**
	 * Copies all matching member names while maintaining original types and
	 *   doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $in,
	 *   leaves unmatched properties in this class untouched, and
	 *   skips names starting with an underscore.
	 *
	 * Input can be an object, an associative array, or
	 *   a JSON string representing a non-scalar type.
	 *
	 * @param object|array|string|bool|null $in -OPTIONAL
	 */
	public function assignObject($in = null);


	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/converstion to an array from objects is also performed where appropriate.
	 *
	 * @return array
	 */
	public function toArray();


	/**
	 * Returns JSON string representing the simple form (toArray) of this object.
	 * Optionally retruns a pretty-print string.
	 *
	 * Use ToJsonTrait.php
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	public function toJson($pretty = false);


	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an array where the values are the names of members to include.
	 * An empty array means to use all.
	 *
	 * @param array $include
	 * @return string
	 */
// 	public function getSqlInsert(array $include = []);


	/**
	 * Returns a string formatted for an SQL
	 * "ON DUPLICATE KEY UPDATE" statement.
	 *
	 * Accepts an array where the values are the names of members to include.
	 * An empty array means to use all members.
	 *
	 * @param array $include
	 * @return string
	 */
// 	public function getSqlValues(array $include = []);

}
