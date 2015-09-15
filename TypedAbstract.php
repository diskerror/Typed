<?php

namespace Typed;

use Countable;

/**
 * Provides common interface and core methods for TypedClass and TypedArray.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
abstract class TypedAbstract implements Countable
{
	/*
	 * Required method for Countable.
	 * @return int
	 */
	abstract public function count();


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
	abstract public function assignObject($in = null);


	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/converstion to an array from objects is also performed where appropriate.
	 *
	 * @return array
	 */
	abstract public function toArray();


	//	Test whether a supplied array is indexed or associative.
	final protected static function _isIndexedArray(array &$in)
	{
		return (array_values($in) === $in);
	}

	//	json_decode fails silently and an empty array is returned.
	final protected static function _jsonDecode(&$in)
	{
		$out = json_decode( $in, true );
		if ( !is_array($out) ) {
			return [];
		}
		return $out;
	}

	//	Empty array or object (no members) is false. Any property or index then true. (Like PHP 4)
	final protected static function _castToBoolean(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (boolean) $in->toArray();
			}
			return (boolean) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (boolean) $in;
		}
	}

	//	Empty array or object (no members) is 0. Any property or index then 1 (Like PHP 4).
	final protected static function _castToInteger(&$in)
	{
		switch ( gettype($in) ) {
			case 'string':
			return intval($in, 0);

			case 'object':
			return (integer) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (integer) $in;
		}
	}

	//	Empty array or object (no members) is 0.0. Any property or index then 1.0. (Like PHP 4)
	final protected static function _castToDouble(&$in)
	{
		switch ( gettype($in) ) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (double) $in->toArray();
			}
			return (double) (array) $in;

			case 'null':
			case 'NULL':
			return null;

			default:
			return (double) $in;
		}
	}

	//	Empty array or object (no members) is "". Any property or index then "1". (Like PHP 4)
	final protected static function _castToString(&$in)
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
			return null;

			default:
			return (string) $in;
		}
	}

	final protected static function _castToArray(&$in)
	{
		if ( is_object($in) && method_exists($in, 'toArray') ) {
			return $in->toArray();
		}
		return (array) $in;
	}

}
