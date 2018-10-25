<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


class SAStringTrim extends SAString
{
	public function set($in)
	{
		parent::set($in);
		if (null !== $this->_value) {
			$this->_value = trim($this->_value, "\x01..\x20");
		}
	}
}
