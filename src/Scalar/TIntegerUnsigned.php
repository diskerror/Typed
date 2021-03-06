<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\Scalar\TIntegerUnsigned
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;


use Diskerror\Typed\Scalar\TInteger;

class TIntegerUnsigned extends TInteger
{
	public function set($in)
	{
		parent::set($in);

		//	If null was set in parent it casts to zero so null stays null.
		if ($this->_value < 0) {
			$this->_value = 0;
		}
	}
}
