<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed\Scalar;


class Integer extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'string':
				$in = trim(strtolower($in), "\x00..\x20\x7F");
				/*****************			If empty string or string with text "null" */
				$this->_value = ($this->_allowNull && ($in === '' || $in === 'null')) ? null : (int)intval($in, 0);
				break;

			case 'object':
				$this->_value = method_exists($in, 'toArray') ? (int)$in->toArray() : (int)(array)$in;
				break;

			case 'null':
			case 'NULL':
				$this->_value = $this->_allowNull ? null : 0;
				break;

			default:
				$this->_value = (int)$in;
		}
	}
}
