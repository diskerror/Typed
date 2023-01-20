<?php

namespace Diskerror\Typed;

use ErrorException;

/**
 * Our settype() only using keyword casting.
 */
trait SetTypeTrait
{
	/**
	 * @param        $val
	 * @param string $type
	 *
	 * @return array|bool|float|int|string|null
	 * @throws ErrorException
	 */
	public static function setType($val, string $type)
	{
		switch ($type) {
			case 'string':
				return (string) $val;

			case 'int':
			case 'integer':
			case 'long':
				return (int) $val;

			case 'float':
			case 'double':
			case 'real':
				return (float) $val;

			case 'bool':
			case 'boolean':
				return (bool) $val;

			case 'null':
			case 'NULL':
				return null;

			case 'array':
				//	simple compare
				return '' == $val ? [] : (array) $val;

			default:
				throw new ErrorException('bad type name');
		}
	}
}
