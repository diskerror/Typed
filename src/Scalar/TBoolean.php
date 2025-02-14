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
	public function set(mixed $in): void
	{
		if ($in === null) {
			$this->_value = $this->isNullable() ? null : false;
		} else {
			$this->_value = (bool)self::_castIfObject($in);
		}
	}
}
