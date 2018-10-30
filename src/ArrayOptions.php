<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 7/6/18
 * Time: 6:36 PM
 */

namespace Diskerror\Typed;

/**
 * Class ArrayOptions
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
	 * Sometimes we want MongoDB to generate an "_id" that we will want the Persist
	 * mechanism to return on find. This only omits the top level _id on saving.
	 * Don't use this when you want to create and save your own _id.
	 */
	const OMIT_ID = 4;

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
	 * Setting this will instruct the conversion to an array to leave "_id" alone.
	 */
	const NO_CAST_BSON_ID = 32;

	/**
	 * @var int
	 */
	private $_options;

	/**
	 * @param int $opts
	 */
	public function __construct(int $opts = 0)
	{
		$this->_options = $opts;
	}

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

	/**
	 * @param int $opt
	 *
	 * @return bool
	 */
	public function has(int $opt) : bool
	{
		return ($this->_options & $opt) > 0;
	}
}
