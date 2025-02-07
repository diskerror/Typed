<?php

namespace TestClasses\Preferences;

use Diskerror\TypedBSON\TypedArray;

class ItemList extends TypedArray
{
	protected $_type = Item::class;

	/**
	 * Return new default set of fields with each call.
	 *
	 * @return ItemList
	 */
	public static function getDefault(): ItemList
	{
		return new self([
			'name'    => [],
			'address' => [],
			'city'    => [],
			'state'   => [],
			'zip'     => [],
		]);
	}

}
