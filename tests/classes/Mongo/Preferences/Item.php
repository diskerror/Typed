<?php
/** @noinspection ALL */
/** @noinspection ALL */
/** @noinspection ALL */

namespace TestClasses\Preferences;

use TestClasses\Preferences\Item\{Compare, Operator, Sort};
use Diskerror\TypedBSON\TypedClass;

class Item extends TypedClass
{
//	const BOOLEAN = 'AND|OR';
//	const COMPARE = '|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN';
//	const SORT    = '|ASC|DESC';

	protected $included = true;                            //	Include this in the view.
	protected $operator = [Operator::class, 'AND'];        //	AND, OR
	protected $compare  = [Compare::class, 'LIKE'];        //	=, <, >, LIKE, REGEXP, IN, etc.
	protected $find     = '';                              //	search string
	protected $sort     = [Sort::class, 'ASC'];            //	ASC, DESC, sort direction or nothing

//	protected function _set_operator($v)
//	{
//		//	These will have been cast to a SAString object.
//		$this->operator->set(strtoupper(preg_replace('/(' . self::BOOLEAN . ')/i', '$1', $v)));
//	}
//
//	protected function _set_compare($v)
//	{
//		$this->compare->set(strtoupper(preg_replace('/(' . self::COMPARE . ')/i', '$1', $v)));
//	}
//
//	protected function _set_sort($v)
//	{
//		$this->sort->set(strtoupper(preg_replace('/(' . self::SORT . ')/i', '$1', $v)));
//	}

}
