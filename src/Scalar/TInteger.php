<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TInteger
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

class TInteger extends ScalarAbstract
{
	public function set(mixed $in): void
	{
		$in = self::_castIfObject($in);

		switch (gettype($in)) {
			case 'string':
				$in = trim(strtolower($in), "\x00..\x20\x7F");
				/**   If empty string or string with text "null" or "nan" */
				if ($in === '' || $in === 'null' || $in === 'nan') {
					$this->_value = $this->isNullable() ? null : 0;
				}
				else {
					$this->_value = intval($in, 0);
				}
				break;

			case 'NULL':
				$this->_value = $this->isNullable() ? null : 0;
				break;

			default:
				$this->_value = (int) $in;
		}
	}
}
