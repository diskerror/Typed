<?php
/**
 * Methods for maintaining variable type.
 * @name		TypedAbstract
 * @copyright	Copyright (c) 2012 Reid Woodbury Jr.
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Countable;

/**
 * Provides common interface and core methods for TypedClass and TypedArray.
 */
abstract class TypedAbstract implements Countable
{
	/**
	 * Required method for Countable.
	 * @return int
	 */
	abstract public function count();


	/**
	 * Copies all matching member names while maintaining original types and
	 *	 doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $in,
	 *	 leaves unmatched properties in this class untouched, and
	 *	 skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	abstract public function assignObject($in = null);


	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/converstion to an array from objects is also performed where appropriate.
	 *
	 * @return array
	 */
	abstract public function toArray();


	/**
	 * Empty array or object (no members) is false.
	 * Any property or index then true. (Like PHP 4)
	 *
	 * @param mixed $in
	 * @return bool
	 */
	protected static function _castToBoolean(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (boolean) $in->toArray();
			}

			return (boolean) (array) $in;

			case 'null':
			case 'NULL':
			return;

			default:
			return (boolean) $in;
		}
	}

	/**
	 * Empty array or object (no members) is 0.
	 * Any property or index then 1. (Like PHP 4)
	 *
	 * @param mixed $in
	 * @return int
	 */
	protected static function _castToInteger(&$in)
	{
		switch ( gettype($in) ) {
			case 'string':
			return intval($in, 0);

			case 'object':
			return (integer) (array) $in;

			case 'null':
			case 'NULL':
			return;

			default:
			return (integer) $in;
		}
	}

	/**
	 * Empty array or object (no members) is 0.0.
	 * Any property or index then 1.0. (Like PHP 4)
	 *
	 * @param mixed $in
	 * @return double
	 */
	protected static function _castToDouble(&$in)
	{
		switch ( gettype($in) ) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (double) $in->toArray();
			}

			return (double) (array) $in;

			case 'null':
			case 'NULL':
			return;

			default:
			return (double) $in;
		}
	}

	/**
	 * Empty array or object (no members) is "".
	 * Any property or index then "1". (Like PHP 4)
	 *
	 * @param mixed $in
	 * @return string
	 */
	protected static function _castToString(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, '__toString') ) {
				return $in->__toString();
			}
			if ( method_exists($in, 'format') ) {
				return $in->format('c');
			}
			if ( method_exists($in, 'toArray') ) {
				$in = $in->toArray();
			}
			//	other objects fall through, object to array falls through
			case 'array':
			return json_encode($in);

			case 'null':
			case 'NULL':
			return;

			default:
			return (string) $in;
		}
	}

	/**
	 * Cast all input to an array.
	 *
	 * @param mixed $in
	 * @return array
	 */
	protected static function _castToArray(&$in)
	{
		if ( is_object($in) && method_exists($in, 'toArray') ) {
			return $in->toArray();
		}

		return (array) $in;
	}
}