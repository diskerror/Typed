<?php

/**
 * Provides common interface for Typed and TypedArray.
 *
 * @copyright  Copyright (c) 2015 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
interface Typed\Interface
{
	/**
	 * Copies all matching property names while maintaining original types and
	 *   doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $obj,
	 *   leaves unmatched properties in this class untouched, and
	 *   skips names starting with an underscore.
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 *	property (property names not starting with an underscore). Extra values
	 *	are ignored. Unused properties are unchanged.
	 *
	 * Input can be an object, an associative array, or
	 *   a JSON string representing an object.
	 *
	 * @param object|array|string|bool|null $in -OPTIONAL
	 */
	public function assignObject($in = null);

	/**
	 * Returns an array with all public, protected, and private properties in
	 * object that DO NOT begin with an underscore. This allows protected or
	 * private properties to be treated as if they were public. This supports the
	 * convention that protected and private property names begin with an
	 * underscore (_). Use "__get" and "__set" to access individual names.
	 *
	 * @return array
	 */
	public function toArray();

	/**
	 * Returns JSON string representing the object.
	 * Optionally retruns a pretty-print string.
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	public function toJson($pretty = false);
	
}
