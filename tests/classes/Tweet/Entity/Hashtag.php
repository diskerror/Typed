<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{Scalar\TInteger, TypedArray, TypedClass};

class Hashtag extends TypedClass
{
	public string        $text = '';
	protected TypedArray $indices;

	public function __construct($in = null)
	{
		$this->indices = new TypedArray(TInteger::class);
		parent::__construct($in);
	}
}
