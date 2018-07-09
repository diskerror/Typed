<?php
/**
 * Methods for maintaining variable type.
 * @name        TypedInterface
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

/**
 * Class TypedInterface
 * Provides common interface TypedClass and TypedArray.
 * @package Diskerror\Typed
 */
interface TypedInterface
{
	/**
	 * Copies all matching member names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $in,
	 *     leaves unmatched properties in this class untouched, and
	 *     skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 * Indexed arrays are copied by position.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	function assign($in = null);

	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/converstion to an array from objects is also performed
	 * where appropriate.
	 *
	 * @return array
	 */
	function toArray() : array;

	/**
	 * @return int
	 */
	function getArrayOptions() : int;

	/**
	 * @param int $opt
	 */
	function setArrayOptions(int $opt);

}
