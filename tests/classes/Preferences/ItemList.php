<?php

namespace TestClasses\Preferences;

use Diskerror\Typed\TypedArray;

class ItemList extends TypedArray
{
	protected string $_type = Item::class;

	/**
	 * Return new initValue set of fields with each call.
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
