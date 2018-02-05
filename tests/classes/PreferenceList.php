<?php

require_once 'PreferenceItem.php';

class PreferenceList extends \Diskerror\Typed\TypedArray
{
	protected $_type = 'PreferenceItem';

	/**
	 * Return new default set of fields with each call.
	 *
	 * @return Application_Model_ColumnOrder
	 */
	public static function getDefault()
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
