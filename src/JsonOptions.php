<?php
/**
 * Manages the bit-wise options for converting a typed object into an associative array.
 *
 * @name           ArrayOptions
 * @copyright      Copyright (c) 2017 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

/**
 * Class ArrayOptions
 *
 * @package Diskerror\Typed
 */
class JsonOptions extends Options
{
	/**
	 * Omit empty properties from the array that is output.
	 */
	const OMIT_EMPTY = 1;

	/**
	 * Convert all other objects to string, if possible.
	 */
	const ALL_OBJECTS_TO_STRING = 2;

	/**
	 * For Zend JSON encoding to JSON, these objects contain strings that should not be quoted.
	 */
	const KEEP_JSON_EXPR = 4;
}
