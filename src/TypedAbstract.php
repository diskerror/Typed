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
 * Provides common interface and core methods for TypedClass and TypedArray.
 *
 * @package Diskerror\Typed
 */
abstract class TypedAbstract implements Countable, IteratorAggregate, JsonSerializable
{
	/**
	 * Holds options for reducing this object to a PHP array or for serializations.
	 *
	 * @var ConversionOptions
	 */
	public ConversionOptions $conversionOptions;

	use IsTypeTrait;

	/**
	 * Coerces input to given scalar or array type. Objects are not changed.
	 *
	 * @param mixed  $val
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _setBasicTypeAndConfirm(mixed &$val, string $type): bool {
		$valType = gettype($val);

		switch ($type) {
			case '':
			case 'NULL':
			break;

			case 'resource':
			case 'callable':
				if ($valType !== $type) {
					$val = true;
				}
			break;

			case 'string':
				switch ($valType) {
					case 'object':
						if (method_exists($val, '__toString')) {
							$val = (string)$val;
							break;
						}
						// fall through
					case 'array':
						$val = json_encode($val, JSON_THROW_ON_ERROR);
					break;

					default:
						$val = (string)$val;
				}
			break;

			case 'int':
			case 'integer':
				switch ($valType) {
					case 'object':
						if ($val instanceof AtomicInterface) {
							$val = (int)$val->get();
							break;
						}
					//	else fall through
					case 'array':
						$val = count((array)$val);
					break;

					default:
						$val = (int)$val;
				}
			break;

			case 'float':
			case 'double':
				$val = (float)$val;
			break;

			case 'bool':
			case 'boolean':
				switch ($valType) {
					case 'array':
						$val = !empty($val);
					break;

					case 'object':
						if ($val instanceof AtomicInterface) {
							$val = (bool)$val->get();
						} else {
							$val = !empty((array)$val);
						}
					break;

					default:
						$val = (bool)$val;
				}
			break;

			case 'array':
				$val = (array)$val;
			break;

			default:
				return false;
		}

		return true;
	}

	/**
	 * Assign. Replace values by name.
	 *
	 * Assign values from input object. Only named input items are copied.
	 * Missing keys are left untouched. Deep copy is performed.
	 *
	 * @param mixed $in
	 */
	abstract public function assign($in): void;

	/**
	 * Clear all values.
	 *
	 * All values are set to zero or empty.
	 *
	 * @return void
	 */
	abstract public function clear(): void;

	/**
	 * Merge $this struct with $in struct and return new structure. Input
	 * values will assign cloned values where keys match.
	 *
	 * @param mixed $in
	 *
	 * @return self
	 */
	abstract public function merge($in): self;

	/**
	 * @return void
	 */
	abstract public function setConversionOptionsToNested(): void;

	/**
	 * Returns an array with all public, protected, and private properties in
	 * object that DO NOT begin with an underscore, except "_id". This allows
	 * protected or private properties to be treated as if they were public.
	 * This supports the convention that protected and private property names
	 * begin with an underscore (_).
	 *
	 * @return array
	 */
	abstract public function toArray(): array;

	/**
	 * JsonSerializable::jsonSerialize()
	 *
	 * Called automatically when object is passed to json_encode().
	 *
	 * @return array
	 */
	abstract public function jsonSerialize(): array;

	/**
	 * Check if the input data is good or needs to be massaged.
	 *
	 * @param $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _massageInput(&$in): void {
		switch (gettype($in)) {
			case 'array':
			case 'object':
				// Leave these as is.
			break;

			case 'NULL':
				$in = [];
			break;

			case 'string':
				if ('' === $in) {
					$in = [];
				} else {
					$in = json_decode($in, true, 512, JSON_THROW_ON_ERROR | JSON_BIGINT_AS_STRING);
				}
			break;

			case 'boolean':
				// A 'false' is returned by many DB APIs for "no results".
				if (true !== $in) {
					/** Change false to empty array. */
					$in = [];
					break;
				}
			//	A boolean 'true' falls through.

			default:
				throw new InvalidArgumentException(get_called_class() . ': bad input type ' . gettype($in) . ', value: "' . $in . '"');
		}
	}
}
