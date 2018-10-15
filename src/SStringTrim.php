<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


class StringTrim extends String
{
	public function set($in)
	{
		parent::set($in);
		$this->_value = trim($this->_value, "\x00..\x20\x7F");
	}
}
