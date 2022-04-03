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
	protected ArrayOptions $_arrayOptions;

	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var ArrayOptions
	 */
	protected ArrayOptions $_serializeOptions;

	/**
	 * Holds options for "toArray" customizations when used by json_encode.
	 *
	 * @var ArrayOptions
	 */
	protected ArrayOptions $_jsonOptions;


	protected function __construct($param1 = null, $param2 = null)
	{
		/**
		 * Initialize options for when this object is converted to an array.
		 */
		$this->_arrayOptions     = new ArrayOptions();
		$this->_serializeOptions = new ArrayOptions(ArrayOptions::OMIT_DEFAULTS);
		$this->_jsonOptions      =
			new ArrayOptions(ArrayOptions::OMIT_DEFAULTS | ArrayOptions::KEEP_JSON_EXPR);
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
	 * Missing keys are left untouched.
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
	abstract protected function _massageInput(&$in): void;

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
		return $this->_toArray($this->_arrayOptions);
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
	 * @return int
	 */
	public function getArrayOptions(): int
	{
		return $this->_arrayOptions->get();
	}

	/**
	 * @param int $opt
	 */
	public function setArrayOptions(int $opts): void
	{
		$this->_arrayOptions->set($opts);
	}

	/**
	 * Be sure json_encode gets our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return $this->_toArray($this->_jsonOptions);
	}

	protected static function _isAssignableType(string $type): bool
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
}
