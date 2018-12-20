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
use Serializable;
use JsonSerializable;

/**
 * Class TypedInterface
 * Provides common interface TypedClass and TypedArray.
 *
 * @package Diskerror\Typed
 */
abstract class TypedAbstract implements Countable, IteratorAggregate, Serializable, JsonSerializable
{
	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var \Diskerror\Typed\ArrayOptions
	 */
	protected $_arrayOptions;

	/**
	 * Holds default options for "toArray" customizations.
	 *
	 * @var int
	 */
	protected $_arrayOptionDefaults = 0;

	/**
	 * Assign.
	 *
	 * Assign values from input object. Missing keys are set to their
	 * default values.
	 *
	 * @param mixed $in
	 */
	abstract function assign($in);

	/**
	 * Replace.
	 *
	 * Assign values from input object. Missing keys are left untouched.
	 *
	 * @param mixed $in
	 */
	abstract function replace($in);

	/**
	 * Merge $this struct with $in struct into new structure. Input values
	 * will replace cloned values where they match.
	 *
	 * @param mixed $in
	 * @return self
	 */
	abstract function merge($in);

	/**
	 * Returns an array of this object with only the appropriate members.
	 * A deep copy/conversion to an array from objects is also performed
	 * where appropriate.
	 *
	 * @return array
	 */
	abstract function toArray(): array;

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

}
