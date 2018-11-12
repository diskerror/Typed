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
	 * Copies all matching member names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $in,
	 *     leaves unmatched properties in this class untouched, and
	 *     skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 * Indexed arrays are copied by position.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	abstract function assign($in = null);

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
