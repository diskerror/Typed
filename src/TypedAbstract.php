<?php
/**
 * Methods for maintaining variable type.
 *
 * @name           TypedAbstract
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class TypedAbstract
 * Provides common interface TypedClass and TypedArray.
 *
 * @package Diskerror\Typed
 */
abstract class TypedAbstract implements Countable, IteratorAggregate, JsonSerializable
{
	/**
	 * Holds options for "toArray" customizations.
	 * Protected for read-only access.
	 *
	 * @var ArrayOptions
	 */
	protected $toArrayOptions;

	/**
	 * Holds options for "__serialize" customizations.
	 * Protected for read-only access.
	 *
	 * @var ArrayOptions
	 */
	protected $serializeOptions;

	/**
	 * Holds options for "toArray" customizations when used by json_encode.
	 * Protected for read-only access.
	 *
	 * @var ArrayOptions
	 */
	protected $toJsonOptions;

	/**
	 * Assign.
	 *
	 * Assign values from input object. Missing keys are set to their
	 * default values.
	 *
	 * @param mixed $in
	 */
	abstract public function assign($in);

	/**
	 * Replace.
	 *
	 * Assign values from input object. Only named input items are copied.
	 * Missing keys are left untouched.
	 *
	 * @param mixed $in
	 */
	abstract public function replace($in);

	/**
	 * Merge $this struct with $in struct and return new structure. Input
	 * values will replace cloned values where keys match.
	 *
	 * @param mixed $in
	 *
	 * return called class
	 */
	abstract public function merge($in);

	/**
	 * Initialize options for when this object is converted to an array.
	 */
	protected function _initArrayOptions()
	{
		$this->toArrayOptions   = new ArrayOptions();
		$this->serializeOptions = new ArrayOptions(ArrayOptions::OMIT_RESOURCE);
		$this->toJsonOptions    = new ArrayOptions(
			ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::KEEP_JSON_EXPR);
	}

	static final protected function _isArrayOption(string $name): bool
	{
		switch ($name) {
			case 'toArrayOptions':
			case 'serializeOptions':
			case 'toJsonOptions':
				return true;
		}
		return false;
	}

	/**
	 * Check if the input data is good or needs to be massaged.
	 *
	 * @param $in
	 *
	 * @throws InvalidArgumentException
	 */
	final protected function _massageInput(&$in): void
	{
		switch (gettype($in)) {
			case 'string':
				if ('' === $in) {
					$in = [];
				}
				else {
					$in        = json_decode($in);
					$lastError = json_last_error();
					if ($lastError !== JSON_ERROR_NONE) {
						throw new InvalidArgumentException(
							'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
							$lastError
						);
					}
				}
				break;

			case 'object':
			case 'array':
				// Leave these as is.
				break;

			case 'null':
			case 'NULL':
				$in = [];
				break;

			case 'bool':
			case 'boolean':
				// A 'false' is returned by MySQL:PDO for "no results".
				if (true !== $in) {
					/** Change false to empty array. */
					$in = [];
				}
			//	A boolean 'true' falls through.

			default:
				throw new InvalidArgumentException('bad input type ' . gettype($in) . ', value: "' . $in . '"');
		}
	}

	/**
	 * Returns an array with all public, protected, and private properties in
	 * object that DO NOT begin with an underscore, except "_id". This allows
	 * protected or private properties to be treated as if they were public.
	 * This supports the convention that protected and private property names
	 * begin with an underscore (_).
	 *
	 * @return array
	 */
	final public function toArray(): array
	{
		return $this->_toArray($this->toArrayOptions);
	}

	/**
	 * Override to provide the actual toArray code with desired options.
	 *
	 * @param ArrayOptions $arrayOptions
	 *
	 * @return array
	 */
	abstract protected function _toArray(ArrayOptions $arrayOptions): array;

	/**
	 * Be sure json_encode gets our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return $this->_toArray($this->toJsonOptions);
	}
}
