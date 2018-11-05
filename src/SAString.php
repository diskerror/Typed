<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAString
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use UnexpectedValueException;

/**
 * Class SAString
 *
 * In PHP, a string variable can hold any set of bytes, so we call that a binary, short for "binary string".
 *
 * We don't want certain bytes in a text string.
 *
 * Is removing "\x7F" safe for UTF8 strings?
 *
 * @package Diskerror\Typed
 */
class SAString extends SABinary
{
	/**
	 * @param $in
	 */
	public function set($in)
	{
		parent::set($in);
		if (null !== $this->_value) {
			$this->_value = strtr($this->_value, ["\x00" => '', "\x7F" => '']);
		}
	}
}
