<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\SAFloat
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


class SAFloat extends SAScalar
{
	public function set($in)
	{
		parent::set($in);

		switch (gettype($this->_value)) {
			case 'string':
				$str = trim(strtolower($this->_value), "\x00..\x20\x7F");
				if ($str === '' || $str === 'null' || $str === 'nan') {
					$this->unset();
					break;
				}

				$str = str_replace(['\'', '"', '“', '”', '‘', '’', ' '], '', $str);
				$str = preg_replace('/^([-+0-9.,]*).*?$/', '$1', $str);

				$comaPos = strpos($str, ',');
				$dotPos  = strpos($str, '.');

				if ($comaPos !== false && $dotPos !== false) {
					if ($comaPos > $dotPos) {
						$str = str_replace(['.', ','], ['', '.'], $str);
					}
					else {
						$str = str_replace(',', '', $str);
					}
				}
				elseif ($comaPos !== false) {
					$str = str_replace(',', '.', $str);
				}

				$this->_value = (float)$str;
				break;

			default:
				$this->_value = (float)$this->_value;
		}
	}
}
