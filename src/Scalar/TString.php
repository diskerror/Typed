<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TString
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;
use UnexpectedValueException;

class TString extends ScalarAbstract
{
	public function set($in): void
	{
		if (is_object($in)) {
			// Every object is converted to an array or a string.
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

			case 'resource':
				throw new UnexpectedValueException('Value cannot be a resource.');

			default:
				$this->_value = (string) $in;
		}
	}
}