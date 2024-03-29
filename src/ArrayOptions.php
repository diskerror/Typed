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
class ArrayOptions extends Options
{
	/**
	 * Omit empty properties from the array that is output.
	 */
	const OMIT_EMPTY = 1;

	/**
	 * Omit resources from the array that is output.
	 * This is only meaningful for the toArray() method.
	 * The serialization methods always omit resources.
	 */
	const OMIT_RESOURCE = 2;

	/**
	 * Convert DateInterface objects to string.
	 */
	const DATE_OBJECT_TO_STRING = 4;

	/**
	 * Convert all other objects to string, if possible.
	 */
	const ALL_OBJECTS_TO_STRING = 8;
}
