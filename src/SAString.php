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
		switch (gettype($in)) {
			case 'object':
				if ($in instanceof ScalarAbstract) {
					$this->_value = (string)$in->get();
					break;
				}
				if (method_exists($in, '__toString')) {
					$this->_value = $in->__toString();
					break;
				}
				if (method_exists($in, 'format')) {
					$this->_value = $in->format('c');
					break;
				}

				if (method_exists($in, 'toArray')) {
					$in = $in->toArray();
				}
			//	other objects fall through, object to array falls through
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
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = (string)$in;
		}
	}
}
