<?php
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

namespace TestClasses\Preferences;

use TestClasses\Preferences\Item\{Compare, Operator, Sort};

class Item extends \Diskerror\Typed\TypedClass
{
//	const BOOLEAN = 'AND|OR';
//	const COMPARE = '|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN';
//	const SORT    = '|ASC|DESC';

	protected $included = true;                            //	Include this in the view.
	protected $operator = [Operator::class, 'AND'];        //	AND, OR
	protected $compare  = [Compare::class, 'LIKE'];        //	=, <, >, LIKE, REGEXP, IN, etc.
	protected $find     = '';                              //	search string
	protected $sort     = [Sort::class, 'ASC'];            //	ASC, DESC, sort direction or nothing
}
