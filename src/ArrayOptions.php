<?php
/**
 * Manages the bit-wise options for converting a typed object into an associative array.
 *
 * @name           \Diskerror\Typed\ArrayOptions
 * @copyright      Copyright (c) 2017 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

/**
 * Class ArrayOptions
 *
 * @package Diskerror\Typed
 */
final class ArrayOptions extends Options
{
	/**
	 * Omit empty properties from the array that is output.
	 */
	const OMIT_EMPTY = 1;

	/**
	 * Omit resources from the array that is output.
	 */
	const OMIT_RESOURCE = 2;

	/**
	 * For Zend JSON encoding to JSON, these objects contain strings that should not be quoted.
	 */
	const KEEP_JSON_EXPR = 4;

	/**
	 * The following options are used with the project TypedBSON.
	 */

	/**
	 * Setting this will instruct the conversion to an array to leave MongoDB\BSON instances alone,
	 * don't convert to string.
	 */
	const NO_CAST_BSON = 8;

	/**
	 * Cast all DateTimeInterface objects to UTCDateTime.
	 */
	const CAST_DATETIME_TO_BSON = 16;

	/**
	 * Cast member with the name "_id" into MongoDB\BSON\ObjectId when doing bsonSerialize().
	 */
	const CAST_ID_TO_OBJECTID = 32;

	const SET_ALL_YES = 63;
}
