<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


class Boolean extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'object':
				$this->_value = method_exists($in, 'toArray') ? (bool)$in->toArray() : (bool)(array)$in;
				break;

			case 'null':
			case 'NULL':
				$this->_value = $this->_allowNull ? null : false;
				break;

			default:
				$this->_value = (bool)$in;
		}
	}
}
