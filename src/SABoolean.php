<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


class SABoolean extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'object':
				$this->_value = (bool)self::_castObject($in);
			break;

			case 'null':
			case 'NULL':
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = (bool)$in;
		}
	}
}
