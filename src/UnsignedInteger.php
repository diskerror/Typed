<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


class UnsignedInteger extends Integer
{
	public function set($in)
	{
		parent::set($in);

		//	null casts to zero so is unchanged
		if ($this->_value < 0) {
			$this->_value = 0;
		}
	}
}
