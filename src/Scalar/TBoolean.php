<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        Diskerror\Typed\Scalar\TBoolean
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

class TBoolean extends ScalarAbstract
{
	public function set($in)
	{
		$in = self::_castIfObject($in);

		if ($in === null) {
			$this->unset();
		}
		else {
			$this->_value = is_array($in) ? !empty($in) : (bool) $in;
		}
	}
}
