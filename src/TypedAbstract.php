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
	 * Holds options for "toArray" conversion.
	 *
	 * @var ArrayOptions
	 */
	protected ArrayOptions $toArrayOptions;

	/**
	 * Holds options for "jsonSerialize" customizations.
	 *
	 * @var JsonOptions
	 */
	protected JsonOptions $toJsonOptions;

	/**
	 * Holds list of option instance names to be made read-only accessible.
	 *
	 * @var array
	 */
	protected array $_optionList;


	/**
	 * Assign.
	 *
	 * Assign values from input object. Missing keys are set to their
	 * initValue values.
	 *
	 * @param mixed $in
	 */
	abstract public function assign($in): void;

	/**
	 * Replace.
	 *
	 * Assign values from input object. Only named input items are copied.
	 * Missing keys are left untouched. Deep selective copy is performed.
	 *
	 * @param mixed $in
	 */
	abstract public function replace($in): void;

	/**
	 * Merge $this struct with $in struct and return new structure. Input
	 * values will replace cloned values where keys match.
	 *
	 * @param mixed $in
	 *
	 * @return TypedAbstract
	 */
	abstract public function merge($in): TypedAbstract;

	/**
	 * Initialize options for when this object is converted to an array or serialized.
	 *
	 * @return void
	 */
	protected function _initToArrayOptions()
	{
		$this->_optionList = ['toArrayOptions', 'toJsonOptions'];

		$this->toArrayOptions = new ArrayOptions(
			ArrayOptions::OMIT_RESOURCE | ArrayOptions::DATE_OBJECT_TO_STRING
		);
		$this->toJsonOptions  = new JsonOptions(
			JsonOptions::OMIT_EMPTY | JsonOptions::KEEP_JSON_EXPR
		);
	}

	/**
	 * @return void
	 */
	abstract public function setArrayOptionsToNested(): void;

	/**
	 * @return void
	 */
	abstract public function setJsonOptionsToNested(): void;

	/**
	 * Check if the input data is good or needs to be massaged.
	 *
	 * @param $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _massageInput(&$in): void
	{
		switch (gettype($in)) {
			case 'array':
			case 'object':
				// Leave these as is.
				break;

			case 'null':
			case 'NULL':
				$in = [];
				break;

			case 'string':
				if ('' === $in) {
					$in = [];
				}
				else {
					$in        = json_decode($in, JSON_OBJECT_AS_ARRAY);
					$lastError = json_last_error();
					if ($lastError !== JSON_ERROR_NONE) {
						throw new InvalidArgumentException(
							'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
							$lastError
						);
					}
				}
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
	 * Assignable types can be simply assigned, as in $a = $b.
	 * The remainders would be objects which often need to be cloned.
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _isAssignable(string $type): bool
	{
		if (self::_isScalar($type)) {
			return true;
		}

		switch ($type) {
			case 'array':
			case 'resource':
			case 'callable':
				return true;
		}

		return false;
	}

	/**
	 * Assignable types can be simply assigned, as in $a = $b.
	 * The remainders would be objects which often need to be cloned.
	 *
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _isScalar(string $type): bool
	{
		switch ($type) {
			case 'scalar':
			case 'string':
			case 'int':
			case 'integer':
			case 'long':
			case 'float':
			case 'double':
			case 'real':
			case 'numeric':
			case 'bool':
			case 'boolean':
			case 'null':
			case 'NULL':
				return true;
		}

		return false;
	}

	/**
	 * The function settype() may return different values than type casting.
	 *
	 * @param        $val
	 * @param string $type
	 *
	 * @return bool
	 */
	final protected static function _setBasicTypeAndConfirm(&$val, string $type): bool
	{
		switch ($type) {
			case '':
			case 'resource':
			case 'callable':
				break;

			case 'string':
				switch (gettype($val)) {
					case 'array':
					case 'object':
						$val       = json_encode($val);
						$lastError = json_last_error();
						if ($lastError !== JSON_ERROR_NONE) {
							throw new InvalidArgumentException(
								'problem converting input data to JSON: ' . json_last_error_msg(),
								$lastError
							);
						}
						break;

					default:
						$val = (string) $val;
				}
				break;

			case 'int':
			case 'integer':
				switch (gettype($val)) {
					case 'object':
						if (is_a($val, AtomicInterface::class)) {
							$val = $val->get();
							break;
						}
					//	else fall through
					case 'array':
						$val = count($val);
						break;

					default:
						$val = (int) $val;
				}
				break;

			case 'float':
			case 'double':
			case 'real':
			case 'numeric':    //	??
				$val = (float) $val;
				break;

			case 'bool':
			case 'boolean':
				switch (gettype($val)) {
					case 'array':
						$val = !empty($val);
						break;

					case 'object':
						if (is_a($val, AtomicInterface::class)) {
							$val = (bool) $val->get();
						}
						else {
							$val = !empty((array) $val);
						}
						break;

					default:
						$val = (bool) $val;
				}
				break;

			case 'null':
			case 'NULL':
				$val = null;
				break;

			case 'array':
				$val = (array) $val;
				break;

			default:
				return false;
		}

		return true;
	}

	/**
	 * Our version of what should be considered empty.
	 *
	 * @return bool
	 */
	protected static function _isEmpty($v): bool
	{
		switch (gettype($v)) {
			case 'object':
				return empty((array) $v);

			case 'array':
				return $v === [];

			case 'string':
				return $v === '';
		}

		return empty($v);
	}
}
