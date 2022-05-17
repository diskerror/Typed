<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TBoolean
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

class TBoolean extends ScalarAbstract
{
	public function set($in): void
	{
		switch (gettype($in)) {
			case 'object':
				$this->_value = (bool) self::_castIfObject($in);
				break;

			case 'null':
			case 'NULL':
				$this->_value = $this->_allowNull ? null : false;
				break;

			default:
				$this->_value = (bool) $in;
		}
	}
}
