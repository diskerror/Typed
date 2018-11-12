<?php
/**
 * Defines the methods for retrieving and setting a value in a class when we want
 * to represent that class as a single value, generally a scalar, aka. "atomically".
 *
 * @name           \Diskerror\Typed\AtomicInterface
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


interface AtomicInterface
{
	public function get();

	public function set($in);
}
