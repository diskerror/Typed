<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        \Diskerror\Typed\ScalarAbstract
 * @copyright      Copyright (c) 2018 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use stdClass;


/**
 * Class ScalarAbstract
 *
 * @package Diskerror\Typed\Scalar
 */
abstract class ScalarAbstract implements AtomicInterface
{
	/**
	 * Stores the scalar value.
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 * Indicates whether or not the value can also be null.
	 *
	 * @var bool
	 */
	private $_allowNull;

	/**
	 * @var mixed
	 */
	private $_defaultValue;

	/**
	 * ScalarAbstract constructor.
	 *
	 * @param mixed $in A default of an empty string will cast to false or zero as needed.
	 * @param bool  $allowNull
	 */
	public function __construct($in = '', bool $allowNull = false)
	{
		$this->_allowNull = $allowNull;

		if ($allowNull && null === $in) {
			$this->_value        = null;
			$this->_defaultValue = null;
		}
		else {
			$this->set(null === $in ? '' : $in);
			$this->_defaultValue = $this->_value;
		}
	}

	/**
	 * Returns the scalar value.
	 */
	public function get()
	{
		return $this->_value;
	}

	/**
	 * Filters the value before saving.
	 */
	abstract public function set($in);

	/**
	 * Returns true if value is not null.
	 */
	public function isset(): bool
	{
		return isset($this->_value);
	}

	/**
	 * Sets a null or the default value.
	 */
	public function unset()
	{
		$this->_value = $this->_allowNull ? null : $this->_defaultValue;
	}

	/**
	 * @param stdClass $in
	 *
	 * @return mixed
	 */
	protected static function _castObject(stdClass $in)
	{
		if ($in instanceof AtomicInterface) {
			return $in->get();
		}

		if (method_exists($in, '__toString')) {
			return $in->__toString();
		}

		if (method_exists($in, 'format')) {
			return $in->format('c');
		}

		if (method_exists($in, 'toArray')) {
			return $in->toArray();
		}

		return (array)$in;
	}
}
