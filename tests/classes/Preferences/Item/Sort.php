<?php

namespace TestClasses\Preferences\Item;

use Diskerror\Typed\Scalar\TStringTrim;

class Sort extends TStringTrim
{
	public function set($in)
	{
		parent::set($in);
		$this->_value = strtoupper(preg_replace('/(|ASC|DESC)/i', '$1', $this->_value));
	}
}
