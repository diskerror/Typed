<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SABoolean
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


use UnexpectedValueException;

class SABoolean extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'object':
				$this->_value = (bool)self::_castObject($in);
			break;

			case 'null':
			case 'NULL':
				$this->unset();
			break;

			case 'resource':
				throw new UnexpectedValueException('Value cannot be a resource.');

			default:
				$this->_value = (bool)$in;
		}
	}
}
