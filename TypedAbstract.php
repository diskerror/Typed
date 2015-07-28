<?php

namespace Typed;

use Countable;

/**
 * Provides common interface for TypedClass and TypedArray.
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


	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an array where the values are the names of members to include.
	 * An empty array means to use all.
	 *
	 * @param array $include
	 * @return string
	 */
// 	abstract public function getSqlInsert(array $include = []);


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
// 	abstract public function getSqlValues(array $include = []);


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
	final protected static function _convertToBoolean(&$in)
	{
		switch (gettype($in)) {
			case 'object':
			if ( method_exists($in, 'toArray') ) {
				return (double) $in->toArray();
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
	final protected static function _convertToInteger(&$in)
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
	final protected static function _convertToDouble(&$in)
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
	final protected static function _convertToString(&$in)
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

	final protected static function _convertToArray(&$in)
	{
		if ( is_object($in) && method_exists($in, 'toArray') ) {
			return $in->toArray();
		}
		return (array) $in;
	}


	/**
	 * Returns JSON string representing the object.
	 * Optionally retruns a pretty-print string.
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	final public function toJson($pretty = false)
	{
		if ( !function_exists('json_encode') ) {
			throw new BadMethodCallException('json_encode must be available');
		}

		$j = json_encode( $this->toArray() );

		if ( !$pretty ) {
			return $j;
		}

		//	Pretty print from Zend/Json/Json.php v2.4.2.
        $tokens = preg_split('|([\{\}\]\[,])|', $j, -1, PREG_SPLIT_DELIM_CAPTURE);
        $result = "";
        $indent = 0;

        $inLiteral = false;
        foreach ($tokens as $token) {
            $token = trim($token);
            if ($token == "") {
                continue;
            }

            if (preg_match('/^("(?:.*)"):[ ]?(.*)$/', $token, $matches)) {
                $token = $matches[1] . ': ' . $matches[2];
            }

            $prefix = str_repeat("\t", $indent);
            if (!$inLiteral && ($token == '{' || $token == '[')) {
                $indent++;
                if ($result != '' && $result[strlen($result)-1] == "\n") {
                    $result .= $prefix;
                }
                $result .= "$token\n";
            }
            elseif (!$inLiteral && ($token == '}' || $token == ']')) {
                $indent--;
                $prefix = str_repeat("\t", $indent);
                $result .= "\n$prefix$token";
            }
            elseif (!$inLiteral && $token == ',') {
                $result .= "$token\n";
            }
            else {
                $result .= ($inLiteral ?  '' : $prefix) . $token;

                //remove escaped backslash sequences causing false positives in next check
                $token = str_replace('\\', '', $token);
                // Count # of unescaped double-quotes in token, subtract # of
                // escaped double-quotes and if the result is odd then we are
                // inside a string literal
                if ((substr_count($token, '"')-substr_count($token, '\\"')) % 2 != 0) {
                    $inLiteral = !$inLiteral;
                }
            }
        }
		return $result . "\n";
	}


}
