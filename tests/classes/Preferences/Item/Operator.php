<?php

namespace Preferences\Item;

use Diskerror\Typed\Scalar\TStringTrim;

class Operator extends TStringTrim
{
	public function set($in)
	{
		parent::set($in);

		$this->_value = strtoupper(preg_replace('/(|AND|OR)/i', '$1', $this->_value));

		if ($this->_value === '') {
			$this->_value = 'AND';
		}
	}
}
