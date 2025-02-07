<?php

namespace TestClasses\Preferences;

use Diskerror\TypedBSON\TypedArray;

class ListList extends TypedArray
{
	protected $_type = ItemList::class;

	public static function getDefault(): self
	{
		$d = new self([
			'Option Name'    => ItemList::getDefault(),
			'Option Address' => ItemList::getDefault(),
			'Option ZIP'     => ItemList::getDefault()
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
