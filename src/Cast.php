<?php
/**
 * Methods for maintaining variable type.
 * @name        TypedInterface
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


/**
 * Class Cast
 * @package Diskerror\Typed
 */
class Cast
{
	/**
	 * Ya don need to make a instance of dis class tuh use the static methods.
	 */
	protected function __construct() { }

	/**
	 * Empty array or object (no members) is false.
	 * Any property or index then true. (Like PHP 4).
	 *
	 * @param mixed $in
	 *
	 * @return bool|null
	 */
	public static function toBoolean($in) : ?bool
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
	public static function toInteger($in) : ?int
	{
		switch (gettype($in)) {
			case 'string':
				if (strtolower($in) === 'null') {
					return null;
				}

				//	http://php.net/manual/en/function.intval.php
				return intval($in, 0);

			case 'object':
				if (method_exists($in, 'toArray')) {
					return (int)$in->toArray();
				}
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
	public static function toDouble($in) : ?float
	{
		switch (gettype($in)) {
			case 'string':
				$in = trim(strtolower($in));
				if ($in === 'null' || $in === 'nan') {
					return null;
				}

				$in = str_replace(['\'', '"', '“', '”', '‘', '’', ' '], '', $in);
				$in = preg_replace('/^([-+0-9.,]*).*?$/', '$1', $in);

				$comaPos = strpos($in, ',');
				$dotPos  = strpos($in, '.');

				if ($comaPos !== false && $dotPos !== false) {
					if ($comaPos > $dotPos) {
						$in = str_replace(['.', ','], ['', '.'], $in);
					}
					else {
						$in = str_replace(',', '', $in);
					}
				}
				elseif ($comaPos !== false) {
					$in = str_replace(',', '.', $in);
				}

				return (float)$in;

			case 'null':
			case 'NULL':
				return null;

			case 'object':
				if (method_exists($in, 'toArray')) {
					return (float)$in->toArray();
				}
				return (float)(array)$in;

			default:
				return (float)$in;
		}
	}

	/**
	 * Objects or arrays become JSON if they don't have a "__toString" or "format" method.
	 *
	 * We differentiate between a null string and an empty string. Null is NULL and empty is ''.
	 *
	 * @param mixed $in
	 *
	 * @return string|null
	 */
	public static function toString($in) : ?string
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
				$jsonStr     = json_encode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new \UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				return $jsonStr;

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
	public static function toArray($in) : array
	{
		if (is_object($in)) {
			if (method_exists($in, 'toArray')) {
				return $in->toArray();
			}
		}
		elseif (is_string($in)) {
			if (strtolower($in) === 'null') {
				return null;
			}

			$tmpArr = json_decode($in, true);
			if (json_last_error() === JSON_ERROR_NONE) {
				return $tmpArr;
			}
		}

		return (array)$in;
	}

}
