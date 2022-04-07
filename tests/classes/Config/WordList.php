<?php

namespace TestClasses\Config;

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedArray;

class WordList extends TypedArray
{
	protected string $_type = TString::class;
}
