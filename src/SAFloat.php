<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAFloat
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


class SAFloat extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'string':
				$this->_value = trim(strtolower($in), "\x00..\x20\x7F");
				if ($in === '' || $in === 'null' || $in === 'nan') {
					$this->_setNullOrDefault();
					break;
				}

				$in = str_replace(['\'', '"', '“', '”', '‘', '’', ' '], '', $in);
				$in = preg_replace('/^([-+0-9.,]*).*?$/', '$1', $in);

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

				$this->_value = (float)$in;
			break;

			case 'object':
				$this->_value = (float)self::_castObject($in);
			break;

			case 'null':
			case 'NULL':
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = (float)$in;
		}
	}
}
