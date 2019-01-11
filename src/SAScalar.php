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

class SAString extends ScalarAbstract
{
	public function set($in)
	{
		if (is_object($in)) {
			$in = self::_castObject($in);
		}

		switch (gettype($in)) {
			case 'array':
				$jsonStr     = json_encode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				$this->_value = $jsonStr;
			break;

			case 'null':
			case 'NULL':
				$this->unset();
			break;

			default:
				$this->_value = $in;
		}
	}
}
