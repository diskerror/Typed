<?php

namespace TestClasses\Preferences\Item;

use Diskerror\Typed\Scalar\TStringTrim;

class Compare extends TStringTrim
{
	public function set($in): void
	{
		parent::set($in);

		$this->_value =
			strtoupper(preg_replace('/(|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN)/i', '$1', $this->_value));

		if ($this->_value === '') {
			$this->_value = '=';
		}
	}
}
