<?php
/**
 * Defines the methods for retrieving and setting a value in a class when we want
 * to represent that class as a single value, generally a scalar, aka. "atomically".
 *
 * @name           AtomicInterface
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;


/**
 * Defines the methods for retrieving and setting a value in a class when we want
 * to represent that class as a single value, generally a scalar, aka. "atomically".
 */
interface AtomicInterface
{
	public function get(): mixed;

	public function set(mixed $in): void;

	public function isset(): bool;

	public function unset(): void;
}
