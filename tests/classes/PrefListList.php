<?php

class PrefListList extends Diskerror\Typed\TypedArray
{
	protected $_type = 'PreferenceList';

	public static function getDefault()
	{
		$d = new self([
			'Option Name'    => PreferenceList::getDefault(),
			'Option Address' => PreferenceList::getDefault(),
			'Option ZIP'     => PreferenceList::getDefault(),
		]);

		$d['Option Name']['name']->compare     = 'REGEXP';
		$d['Option Name']['name']->find        = '\w';
		$d['Option Name']['name']->sort        = 'ASC';
		$d['Option Name']['address']->included = false;
		$d['Option Name']['city']->included    = false;
		$d['Option Name']['state']->included   = false;
		$d['Option Name']['zip']->included     = false;

		$d['Option Address']['address']->compare = '!=';
		$d['Option Address']['address']->find    = '';
		$d['Option Address']['address']->sort    = 'ASC';
		$d['Option Address']['city']->included   = false;
		$d['Option Address']['state']->included  = false;
		$d['Option Address']['zip']->included    = false;

		$d['Option ZIP']['zip']->compare      = '!=';
		$d['Option ZIP']['zip']->find         = '';
		$d['Option ZIP']['zip']->sort         = 'ASC';
		$d['Option ZIP']['address']->included = false;
		$d['Option ZIP']['city']->included    = false;
		$d['Option ZIP']['state']->included   = false;

		return $d;
	}
}
