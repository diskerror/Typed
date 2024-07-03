<?php

namespace TestClasses\Preferences\Item;

use Diskerror\Typed\Scalar\TStringTrim;

class Sort extends TStringTrim
{
	public function __construct($in = 'ASC', bool $allowNull = false)
	{
		parent::__construct($in, $allowNull);
	}

	public function set($in): void
	{
		parent::set($in);
		$this->_value = strtoupper(preg_replace('/(|ASC|DESC)/i', '$1', $this->_value));
	}
}
