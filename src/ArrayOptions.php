<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/6/18
 * Time: 6:36 PM
 */

namespace Diskerror\Typed;

/**
 *  Options
 * @package Diskerror\Typed
 */
final class ArrayOptions
{
	/**
	 * Omit null variables and empty strings from the array that is output.
	 */
	const OMIT_EMPTY = 1;

	/**
	 * Omit resources from the array that is output.
	 */
	const OMIT_RESOURCE = 2;

	/**
	 * In the "Typed" classes a leading "_" on a variable means it's to considered protected or private.
	 * But MongoDB uses "_id" as the name of the primary key field name for the document. This sets the resulting array
	 * to switch a stored "id_" to "_id".
	 */
	const SWITCH_ID = 4;

	/**
	 * For Zend JSON encoding to JSON, these objects contain strings that should not be quoted.
	 */
	const KEEP_JSON_EXPR = 8;

	/**
	 * All objects with a lineage of DateTime are converted to MongoDB\BSON\UTCDateTime or
	 * this will preserve BSON date objects.
	 */
	const TO_BSON_DATE = 16;

	/**
	 * @var int
	 */
	private $_options = 0;

	/**
	 * @return int
	 */
	public function get() : int
	{
		return $this->_options;
	}

	/**
	 * @param int $opts
	 */
	public function set(int $opts)
	{
		$this->_options = $opts;
	}

	public function has(int $opt) : bool
	{
		return (bool) $this->_options & $opt;
	}

	public function __invoke(int $opt) : bool
	{
		return (bool) $this->_options & $opt;
	}
}
