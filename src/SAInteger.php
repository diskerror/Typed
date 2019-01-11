<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAInteger
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


class SAInteger extends SAScalar
{
	public function set($in)
	{
		parent::set($in);

		switch (gettype($this->_value)) {
			case 'string':
				$str = trim(strtolower($this->_value), "\x00..\x20\x7F");
				/**   If empty string or string with text "null" or "nan" */
				if ($str === '' || $str === 'null' || $str === 'nan') {
					$this->unset();
				}
				else {
					$this->_value = intval($str, 0);
				}
			break;

			default:
				$this->_value = (int)$this->_value;
		}
	}
}
