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
use function json_encode;
use function stat;

class SqlStatement
{
	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an associative array and
	 * an array where the values are the names of the desired keys.
	 * An empty "include" array means to use all.
	 *
	 * @param array $input
	 * @param array $include
	 *
	 * @return string
	 */
	public static function toInsert(array $input, array $include = [])
	{
		if (array_values($input) === $input) {
			throw new InvalidArgumentException('input must be an associative array');
		}

		if (count($include)) {
			$arr = [];
			foreach ($include as $i) {
				if (array_key_exists($i, $input)) {
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

				case 'array':
				case 'object':
					$sqlStrs[] = $kEq . '"' . addslashes(self::_json_encode($v)) . '"';
//					$sqlStrs[] = $kEq . '0x' . bin2hex(self::_json_encode($v));
				break;

				//	resource, just ignore these
				default:
				break;
			}
		}

		return implode(",\n", $sqlStrs);
	}

	/**
	 * Uses the Zend Json encoder if available.
	 */
	protected static function _json_encode($v)
	{
		static $hasEncoder;
		if (!isset($hasEncoder)) {
			$hasEncoder = class_exists('\\Zend\\Json\\Encoder');
		}

		if ($hasEncoder) {
			return \Zend\Json\Encoder::encode($v);
		}
		return json_encode($v);
	}

	/**
	 * Returns a string formatted for an SQL
	 * "ON DUPLICATE KEY UPDATE" statement.
	 *
	 * Accepts an associative array and
	 * an array where the values are the names of the desired keys.
	 * An empty "include" array means to use all.
	 *
	 * @param array $input
	 * @param array $include
	 *
	 * @return string
	 */
	public static function toValues(array $input, array $include = [])
	{
		if (array_values($input) === $input) {
			throw new InvalidArgumentException('input must be an associative array');
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
