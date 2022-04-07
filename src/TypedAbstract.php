<?php
/**
 * Methods for maintaining variable type.
 *
 * @name           TypedAbstract
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use BadMethodCallException;
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
	 *
	 * @var ArrayOptions
	 */
	public ArrayOptions $toArrayOptions;

	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var ArrayOptions
	 */
	public ArrayOptions $serializeOptions;

	/**
	 * Holds options for "toArray" customizations when used by json_encode.
	 *
	 * @var ArrayOptions
	 */
	public ArrayOptions $toJsonOptions;


	/**
	 * @return void
	 */
	protected function _initToArrayOptions()
	{
		/**
		 * Initialize options for when this object is converted to an array.
		 */
		$this->toArrayOptions   = new ArrayOptions();
		$this->serializeOptions = new ArrayOptions(ArrayOptions::OMIT_RESOURCES);
		$this->toJsonOptions    =
			new ArrayOptions(ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCES | ArrayOptions::KEEP_JSON_EXPR);
	}

	/**
	 * Assign.
	 *
	 * Assign values from input object. Missing keys are set to their
	 * default values.
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
	 * Check if the input data is good or needs to be massaged.
	 *
	 * @param $in
	 */
	final protected function _massageInput(&$in): void
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
				if ($in === '') {
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
	 * Protected and private methods will behave like a fried method as in C++.
	 *
	 * @param $name
	 * @param $args
	 * @return mixed
	 */
	public function __call($name, $args)
	{
		$bt = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2);
		if (!is_a($bt[1]['class'], TypedAbstract::class, true)) {
			throw new BadMethodCallException();
		}

		return $this->$name(...$args);
	}


	/**
	 * String representation of PHP object.
	 *
	 * This serialization, as opposed to JSON or BSON, does not unwrap the
	 * structured data. It only omits data that is part of the class definition.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return ?array the string representation of the object or null
	 */
	public function __serialize(): ?array
	{
		$ret                     = [];
		$ret['toArrayOptions']   = $this->toArrayOptions->get();
		$ret['serializeOptions'] = $this->serializeOptions->get();
		$ret['toJsonOptions']    = $this->toJsonOptions->get();

		return $ret;
	}

	/**
	 * Constructs the object from serialized PHP.
	 *
	 * This uses a faster but unsafe restore technique. It assumes that the
	 * serialized data was created by the local serialize method and was
	 * safely stored locally. No type checking is performed on restore. All
	 * data structure members have been serialized so no initialization of
	 * empty need be done.
	 *
	 * @link  https://www.php.net/manual/en/language.oop5.magic.php#object.unserialize
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void
	{
		$this->toArrayOptions   = new ArrayOptions($data['toArrayOptions']);
		$this->serializeOptions = new ArrayOptions($data['serializeOptions']);
		$this->toJsonOptions    = new ArrayOptions($data['toJsonOptions']);
	}

	/**
	 * Be sure json_encode gets our prepared array.
	 *
	 * @return array
	 */
	final public function jsonSerialize(): array
	{
		return $this->_toArray($this->toJsonOptions);
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	final protected static function _isNonObject(string $type): bool
	{
		static $assignableTypes;
		if (!isset($assignableTypes)) {
			$assignableTypes = [
				'string',
				'int', 'integer',
				'float', 'double', 'real',
				'numeric',
				'bool', 'boolean',
				'null', 'NULL',
				'array',
			];
		}

		return in_array($type, $assignableTypes, true);
	}

	/**
	 * The function settype() may return different values than type casting.
	 *
	 * @param $val
	 * @param string $type
	 * @return bool
	 */
	final protected static function _setBasicTypeAndConfirm(&$val, string $type): bool
	{
		switch ($type) {
			case '':
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
}
