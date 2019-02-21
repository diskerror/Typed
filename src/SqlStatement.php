<?php
/**
 * SQL statement generator.
 * Converts associative arrays and objects into partial SQL statements.
 *
 * @name        SqlStatement
 * @copyright   Copyright (c) 2018 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * Class SqlStatement
 *
 * @package Diskerror\Typed
 */
class SqlStatement
{
	/**
	 * Ya don' need to make an instance of this class to use the static methods.
	 */
	protected function __construct()
	{
	}

	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an associative array or Traversable object and
	 * an array where the values are the names of the desired keys.
	 * An empty "include" array means to use all.
	 *
	 * @param array|\stdClass $input
	 * @param array           $include
	 *
	 * @return string
	 */
	public static function toInsert($input, array $include = [])
	{
		if (is_object($input)) {
			if (method_exists($input, 'toArray')) {
				$input = $input->toArray();
			}
			elseif ($input instanceof \Traversable) {
				$input = iterator_to_array($input);
			}
			else {
				$input = (array)$input;
			}
		}
		elseif (!is_array($input)) {
			throw new InvalidArgumentException('input must be an associative array or traversable object');
		}

		if (count($include)) {
			$arr = [];
			foreach ($include as $i) {
				if (isset($input[$i])) {
					$arr[$i] &= $input[$i];
				}
			}
		}
		else {
			$arr = &$input;
		}

		$sqlStrs = [];
		foreach ($arr as $k => &$v) {
			$kEq = '`' . $k . '` = ';
			switch (gettype($v)) {
				case 'bool':
				case 'boolean':
					$sqlStrs[] = $kEq . ($v ? '1' : '0');
					break;

				case 'int':
				case 'integer':
				case 'float':
				case 'double':
					$sqlStrs[] = $kEq . $v;
					break;

				case 'string':
					//	if $v is a string that contains the text 'NULL' then
					if ($v === 'NULL') {
						$sqlStrs[] = $kEq . 'NULL';
					}
//					elseif ($v === '') {
//						//	This condition is required with bin2hex() as we can't use only '0x'.
//						$sqlStrs[] = $kEq . '""';
//				}
					else {
						$sqlStrs[] = $kEq . '"' . addslashes($v) . '"';
//						$sqlStrs[] = $kEq . '0x' . bin2hex($v);
					}
					break;

				case 'null':
				case 'NULL':
					//	if $v is a NULL
					$sqlStrs[] = $kEq . 'NULL';
					break;

				case 'object':
					if ($v instanceof DateTime) {
						$sqlStrs[] = $kEq . '"' . $v . '"';
						break;
					}
					//	other objects fall through
				case 'array':
					$sqlStrs[] = $kEq . '"' . addslashes(json_encode($v)) . '"';
//					$sqlStrs[] = $kEq . '0x' . bin2hex(json_encode($v));
					$jsonLastErr = json_last_error();
					if ($jsonLastErr !== JSON_ERROR_NONE) {
						throw new UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
					}
					break;

				//	resource, just ignore these
				default:
					break;
			}
		}

		return implode(",\n", $sqlStrs);
	}

	/**
	 * Returns a string formatted for an SQL
	 * "ON DUPLICATE KEY UPDATE" statement.
	 *
	 * Accepts an associative array or Traversable object and
	 * an array where the values are the names of the desired keys.
	 * An empty "include" array means to use all.
	 *
	 * @param array|\stdClass $input
	 * @param array           $include
	 *
	 * @return string
	 */
	public static function toValues($input, array $include = [])
	{
		if (!is_array($input) && !is_object($input)) {
			throw new InvalidArgumentException('input must be an associative array or traversable object');
		}

		$sqlStrs = [];

		if (count($include)) {
			foreach ($include as $i) {
				if (array_key_exists($i, $input)) {
					$sqlStrs[] = '`' . $i . '` = VALUES(`' . $i . '`)';
				}
			}
		}
		else {
			foreach ($input as $k => &$v) {
				$sqlStrs[] = '`' . $k . '` = VALUES(`' . $k . '`)';
			}
		}

		return implode(",\n", $sqlStrs);
	}
}
