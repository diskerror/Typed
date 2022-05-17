<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TStringTrim
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

class TStringTrim extends TString
{
	public function set($in): void
	{
		parent::set($in);
		if (null !== $this->_value) {
			$this->_value = trim($this->_value, "\x00..\x20");
		}
	}
}
