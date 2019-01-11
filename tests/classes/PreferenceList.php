<?php

class PreferenceList extends Diskerror\Typed\TypedArray
{
	protected $_type = 'PreferenceItem';

	/**
	 * Return new default set of fields with each call.
	 *
	 * @return PreferenceList
	 */
	public static function getDefault(): PreferenceList
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
