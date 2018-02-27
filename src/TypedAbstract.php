<?php
/**
 * Methods for maintaining variable type.
 * @name        TypedAbstract
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Countable;

/**
 * Provides common interface and core methods for TypedClass and TypedArray.
 */
abstract class TypedAbstract implements Countable
{
	/**
	 * Empty array or object (no members) is false.
	 * Any property or index then true. (Like PHP 4).
	 *
	 * @param mixed $in
	 *
	 * @return bool|null
	 */
	protected static function _castToBoolean($in): ?bool
	{
		switch (gettype($in)) {
			case 'object':
				if (method_exists($in, 'toArray')) {
					return (bool)$in->toArray();
				}

				return (bool)(array)$in;

			case 'null':
			case 'NULL':
				return null;

			default:
				return (bool)$in;
		}
	}

	/**
	 * Empty array or object (no members) is 0.
	 * Any property or index then 1. (Like PHP 4).
	 *
	 * @param mixed $in
	 *
	 * @return int|null
	 */
	protected static function _castToInteger($in): ?int
	{
		switch (gettype($in)) {
			case 'string':
				//	http://php.net/manual/en/function.intval.php
				return intval($in, 0);

			case 'object':
				return (int)(array)$in;

			case 'null':
			case 'NULL':
				return null;

			default:
				return (int)$in;
		}
	}

	/**
	 * Empty array or object (no members) is 0.0.
	 * Any property or index then 1.0. (Like PHP 4).
	 * Attempts to guess appropriate international numeric input style for strings.
	 * If the input is intended as a string of digits with a comma for the thousands separator,
	 *      the comma will be interpreted as the decimal point like locale "de_DE" and
	 *      will stop at the second comma.
	 *
	 * @param mixed $in
	 *
	 * @return float|null
	 */
	protected static function _castToDouble($in): ?float
	{
		switch (gettype($in)) {
			case 'string':
				$input = str_replace(['\'', '"', '“', '”', '‘', '’', ' '], '', $in);
				$input = preg_replace('/^([-+0-9.,]*).*?$/', '$1', $input);

				$comaPos = strpos($input, ',');
				$dotPos = strpos($input, '.');

				if ($comaPos !== false && $dotPos !== false) {
					if ($comaPos > $dotPos) {
						$input = str_replace(['.', ','], ['', '.'], $input);
					}
					else {
						$input = str_replace(',', '', $input);
					}
				}
				elseif ($comaPos !== false) {
					$input = str_replace(',', '.', $input);
				}

				return (float)$input;

			case 'null':
			case 'NULL':
				return null;

			case 'object':
				if (method_exists($in, 'toArray')) {
					return (float)$in->toArray();
				}

			default:
				return (float)$in;
		}
	}

	/**
	 * Empty array or object (no members) is "".
	 * Any property or index then "1". (Like PHP 4).
	 *
	 * @param mixed $in
	 *
	 * @return string|null
	 */
	protected static function _castToString($in): ?string
	{
		switch (gettype($in)) {
			case 'object':
				if (method_exists($in, '__toString')) {
					return $in->__toString();
				}
				if (method_exists($in, 'format')) {
					return $in->format('c');
				}
				if (method_exists($in, 'toArray')) {
					$in = $in->toArray();
				}
			//	other objects fall through, object to array falls through
			case 'array':
				return self::_json_encode($in);

			case 'null':
			case 'NULL':
				return null;

			default:
				return (string)$in;
		}
	}

	/**
	 * Cast all input to an array.
	 * A null input returns an empty array.
	 *
	 * @param mixed $in
	 *
	 * @return array
	 */
	protected static function _castToArray($in): array
	{
		if (is_object($in) && method_exists($in, 'toArray')) {
			return $in->toArray();
		}

		if (is_string($in)) {
			return self::_json_decode($in);
		}

		return (array)$in;
	}

	/**
	 * @param     $value
	 * @param int $options
	 * @param int $depth
	 *
	 * @return string
	 */
	protected static function _json_encode($value, int $options = 0, $depth = 512): string
	{
		return json_encode($value, $options, $depth);
	}

	/**
	 * @param string $json
	 * @param bool   $assoc
	 * @param int    $depth
	 * @param int    $options
	 *
	 * @return mixed
	 */
	protected static function _json_decode(string $json, bool $assoc = false, int $depth = 512, int $options = 0)
	{
		return json_decode($json, $assoc, $depth, $options);
	}


	/**
	 * Required method for Countable.
	 * @return int
	 */
	abstract public function count();

	/**
	 * Copies all matching member names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $in,
	 *     leaves unmatched properties in this class untouched, and
	 *     skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	abstract public function assignObject($in = null);

	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/converstion to an array from objects is also performed
	 * where appropriate.
	 *
	 * @return array
	 */
	abstract public function toArray();

	/**
	 * This is simmilar to "toArray" above except that some conversions are
	 * made to be more compatible to MongoDB or communication to a web browser.
	 *
	 * @param array $opts
	 *
	 * @return array
	 */
	abstract public function getSpecialObj(array $opts = []);
}
