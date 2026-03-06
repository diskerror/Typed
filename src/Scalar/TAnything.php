<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TAnything
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

/**
 * Class TAnything
 *
 * This class allows input to be any scalar type.
 * Some objects and arrays will be cast to a string.
 *
 * @package Diskerror\Typed
 */
class TAnything extends ScalarAbstract
{
	public function set(mixed $in): void
	{
		if ($in === null) {
			$this->_value = $this->isNullable() ? null : false;
		}
		elseif (is_array($in)) {
			$this->_value = json_encode($in);
		}
		else {
			$this->_value = $in;
		}
	}
}
