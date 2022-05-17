<?php
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

namespace TestClasses\Preferences;

use Diskerror\Typed\TypedClass;
use TestClasses\Preferences\Item\{Compare, Operator, Sort};

class Item extends TypedClass
{
//	const BOOLEAN = 'AND|OR';
//	const COMPARE = '|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN';
//	const SORT    = '|ASC|DESC';

	protected bool     $included = true; //	Include this in the view.
	protected Operator $operator;        //	AND, OR
	protected Compare  $compare;         //	=, <, >, LIKE, REGEXP, IN, etc.
	protected string   $find     = '';   //	search string
	protected Sort     $sort;            //	ASC, DESC, sort direction or nothing

	protected function _initializeObjects()
	{
		$this->operator = new Operator('AND');
		$this->compare  = new Compare('LIKE');
		$this->sort     = new Sort('ASC');
	}
}
