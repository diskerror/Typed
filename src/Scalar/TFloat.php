<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TFloat
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\Scalar;

use Diskerror\Typed\ScalarAbstract;

class TFloat extends ScalarAbstract
{
	protected $_value;

	public function set(mixed $in): void
	{
		$in = self::_castIfObject($in);

		switch (gettype($in)) {
			case 'string':
				$in = trim(strtolower($in), "\x00..\x20\x7F");
				if ($in === '' || $in === 'null' || $in === 'nan') {
					$this->_value = $this->isNullable() ? null : 0.0;
					break;
				}

				$in = str_replace(['\'', '"', '“', '”', '‘', '’', ' '], '', $in);
				$in = preg_replace('/^([-+\d.,]*).*?$/', '$1', $in);

				$comaPos = strpos($in, ',');
				$dotPos  = strpos($in, '.');

				if ($comaPos !== false && $dotPos !== false) {
					if ($comaPos > $dotPos) {
						$in = str_replace(['.', ','], ['', '.'], $in);
					}
					else {
						$in = str_replace(',', '', $in);
					}
				}
				elseif ($comaPos !== false) {
					$in = str_replace(',', '.', $in);
				}

				$this->_value = (float) $in;
				break;

			case 'NULL':
				$this->_value = $this->isNullable() ? null : 0.0;
				break;

			default:
				$this->_value = (float) $in;
		}
	}
}
