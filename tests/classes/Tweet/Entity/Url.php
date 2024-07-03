<?php

namespace TestClasses\Tweet\Entity;

use Diskerror\Typed\{Scalar\TInteger, Scalar\TString, TypedArray, TypedClass};

class Url extends TypedClass
{
	protected TString     $url;    //	We could do some fancy filtering for this.
	protected TString     $expanded_url;
	protected TString     $display_url;
	protected TypedArray $indices;

	public function __construct($in = null)
	{
		$this->indices = new TypedArray(TInteger::class);
		parent::__construct($in);
	}
}
