<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAInteger
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


class SAInteger extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'string':
				$in = trim(strtolower($in), "\x00..\x20\x7F");
				/*****************   If empty string or string with text "null" or "nan" */
				if ($in === '' || $in === 'null' || $in === 'nan') {
					$this->_setNullOrDefault();
				}
				else {
					$this->_value = (int)intval($in, 0);
				}
			break;

			case 'object':
				$this->_value = (int)self::_castObject($in);
			break;

			case 'null':
			case 'NULL':
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = (int)$in;
		}
	}
}
