<?php

namespace Diskerror\Typed;

/**
 * Tests to discover a named type's attributes.
 */
trait IsTypeTrait
{

	/**
	 * Assignable types can be simply assigned, as in $a = $b.
	 * The remainders would be objects which often need to be cloned.
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _isAssignable(string $type): bool
	{
		if (self::_isScalar($type)) {
			return true;
		}

		switch ($type) {
			case 'array':
			case 'resource':
			case 'callable':
				return true;
		}

		return false;
	}

	/**
	 * Similar to the function is_scalar() but takes the type name as a string including 'null'.
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _isScalar(string $type): bool
	{
		switch ($type) {
			case 'NULL':
			case 'null':
			case 'bool':
			case 'boolean':
			case 'int':
			case 'integer':
			case 'float':
			case 'double':
			case 'string':
				return true;
		}

		return false;
	}

}