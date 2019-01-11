<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAString
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

class SAString extends SAScalar
{
	public function set($in)
	{
		parent::set($in);
		if (null !== $this->_value) {
			$this->_value = (string)$this->_value;
		}
	}
}
