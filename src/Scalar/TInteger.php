<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        TInteger
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

class TInteger extends ScalarAbstract
{
	public function set($in)
	{
			$in = self::_castIfObject($in);

		switch (gettype($in)) {
			case 'null':
			case 'NULL':
				$this->unset();
				break;

			case 'string':
				$in = trim(strtolower($in), "\x00..\x20\x7F");
				/**   If has string with text "null" or "nan" */
				if ($in === 'null' || $in === 'nan') {
					$this->unset();
				}
				else {
					$this->_value = intval($in, 0);
				}
				break;

			default:
				$this->_value = (int) $in;
		}
	}
}
