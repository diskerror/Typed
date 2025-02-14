<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TIntegerUnsigned
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

class TIntegerUnsigned extends TInteger
{
	public function set(mixed $in): void
	{
		parent::set($in);

		//	Null input is handled in parent.
		if ($this->_value < 0) {
			$this->_value = 0;
		}
	}
}
