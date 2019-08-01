<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        SAIntegerUnsigned
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


class SAIntegerUnsigned extends SAInteger
{
	public function set($in)
	{
		parent::set($in);

		//	null casts to zero so null stays null
		if ($this->_value < 0) {
			$this->_value = 0;
		}
	}
}
