<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        TAnything
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

/**
 * Class SAAnything
 *
 * This class allows input to be any scalar.
 * Some objects and arrays will be cast to a string.
 *
 * @package Diskerror\Typed
 */
class TAnything extends ScalarAbstract
{
	public function set($in)
	{
		if (null === $in) {
			$this->unset();
		}
		else {
			$this->_value = $in;
		}
	}
}
