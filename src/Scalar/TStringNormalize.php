<?php

namespace Diskerror\Typed\Scalar;

use Normalizer;

class TStringNormalize extends TStringTrim
{
	public function set($in): void
	{
		parent::set($in);

		if ($this->_value !== null) {
			$this->_value = preg_replace('/\s+/', ' ', Normalizer::normalize($this->_value, Normalizer::FORM_D));
		}
	}
}
