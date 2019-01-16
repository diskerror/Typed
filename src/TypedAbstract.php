<?php
/**
 * Methods for maintaining variable type.
 *
 * @name           \Diskerror\Typed\TypedAbstract
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Countable;
use IteratorAggregate;
use MongoDB\BSON\Persistable;
use Serializable;
use JsonSerializable;

/**
 * Class TypedInterface
 * Provides common interface TypedClass and TypedArray.
 *
 * @package Diskerror\Typed
 */
abstract class TypedAbstract implements Countable, IteratorAggregate, Serializable, JsonSerializable, Persistable
{
	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var ArrayOptions
	 */
	protected $_arrayOptions;

	/**
	 * Holds default options for "toArray" customizations.
	 *
	 * @var int
	 */
	protected $_arrayOptionDefaults = 0;

	/**
	 * Holds options for "toArray" customizations when used by json_encode.
	 *
	 * @var ArrayOptions
	 */
	protected $_jsonOptions;

	/**
	 * Holds default options for "toArray" customizations when used by json_encode.
	 *
	 * @var int
	 */
	protected $_jsonOptionDefaults =
		ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::KEEP_JSON_EXPR | ArrayOptions::USE_JSON_SERIALIZE;

	/**
	 * Holds options for "toArray" customizations when used by MongoDB.
	 *
	 * @var ArrayOptions
	 */
	protected $_bsonOptions;

	/**
	 * Holds default options for "toArray" customizations when used by MongoDB/bsonSerialize.
	 *
	 * @var int
	 */
	protected $_bsonOptionDefaults =
		ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::TO_BSON_DATE | ArrayOptions::NO_CAST_BSON_ID | ArrayOptions::USE_BSON_SERIALIZE;

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
	 * Assign values from input object. Missing keys are left untouched.
	 *
	 * @param mixed $in
	 */
	abstract public function replace($in);

	/**
	 * Merge $this struct with $in struct and return new structure. Input
	 * values will replace cloned values where they match.
	 *
	 * @param mixed $in
	 *
	 * @return self
	 */
	abstract public function merge($in);

	protected function _initArrayOptions()
	{
		$this->_arrayOptions = new ArrayOptions($this->_arrayOptionDefaults);
		$this->_jsonOptions  = new ArrayOptions($this->_jsonOptionDefaults);
		$this->_bsonOptions  = new ArrayOptions($this->_bsonOptionDefaults);
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
	public function setArrayOptions(int $opts)
	{
		$this->_arrayOptions->set($opts);
	}

	/**
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		return $this->_toArray($this->_bsonOptions);
	}

	/**
	 * Be sure json_encode get's our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->_toArray($this->_jsonOptions);
	}
}
