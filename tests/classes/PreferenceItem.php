<?php

class PreferenceItem extends \Diskerror\Typed\TypedClass
{
	const BOOLEAN = 'AND|OR';
	const COMPARE = '|=|!=|<|>|>=|<=|LIKE|NOT LIKE|REGEXP|NOT REGEXP|IN';
	const SORT = '|ASC|DESC';

	protected $included = true; //	Include this in the view.
	protected $boolean = 'AND'; //	AND, OR
	protected $compare = '';	//	=, <, >, LIKE, REGEXP, IN, etc. or nothing
	protected $find = '';		//	search string
	protected $sort = '';		//	ASC, DESC, sort direction or nothing

	protected function _set_boolean($v)
	{
		$this->boolean = strtoupper( preg_replace( '/(' . self::BOOLEAN . ')/i', '$1', $v ) );
	}

	protected function _set_compare($v)
	{
		$this->compare = strtoupper( preg_replace( '/(' . self::COMPARE . ')/i', '$1', $v ) );
	}

	protected function _set_sort($v)
	{
		$this->sort = strtoupper( preg_replace( '/(' . self::SORT . ')/i', '$1', $v ) );
	}

}
