<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SABoolean
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


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
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = (bool)$in;
		}
	}
}
