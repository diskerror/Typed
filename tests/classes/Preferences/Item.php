<?php
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

namespace TestClasses\Preferences;

use Diskerror\Typed\Scalar\TBoolean;
use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedClass;
use TestClasses\Preferences\Item\{Compare, Operator, Sort};

class Item extends TypedClass
{
//	const BOOLEAN = 'AND|OR';
//	const COMPARE = '|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN';
//	const SORT    = '|ASC|DESC';

	protected TBoolean $included;        //	Include this in the view.
	protected Operator $operator;        //	AND, OR
	protected Compare  $compare;         //	=, <, >, LIKE, REGEXP, IN, etc.
	protected TString  $find;            //	search string
	protected Sort     $sort;            //	ASC, DESC, sort direction or nothing

	public function __construct($in)
	{
		$this->included = new TBoolean(true);

		parent::__construct($in);
	}
}
