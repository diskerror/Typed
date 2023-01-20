<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        ScalarAbstract
 * @copyright   Copyright (c) 2018 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use OutOfBoundsException;

/**
 * Class ScalarAbstract
 *
 * @package Diskerror\Typed\ScalarAbstract
 */
abstract class ScalarAbstract implements AtomicInterface
{
	use SetTypeTrait;

	/**
	 * Stores the scalar value.
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 * Indicates whether the value can also be null.
	 *
	 * @var bool
	 */
	private bool $_allowNull;

	/**
	 * ScalarAbstract constructor.
	 *
	 * @param mixed $in A initValue of an empty string will cast to false or zero as needed.
	 * @param bool  $allowNull
	 */
	public function __construct($in = '', bool $allowNull = false)
	{
		$this->_allowNull = $allowNull;

		if ($in instanceof AtomicInterface) {
			$this->set($in->get());
		}
		else {
			$this->set($in);
		}
	}

	/**
	 * isNullable
	 *
	 * @return bool
	 */
	public function isNullable(): bool
	{
		return $this->_allowNull;
	}

	/**
	 * Returns the scalar value.
	 *
	 * @return mixed
	 */
	public function get()
	{
		return $this->_value;
	}

	/**
	 * Filters the value before setting.
	 *
	 * @param $in
	 *
	 * @return void
	 */
	abstract public function set($in): void;

	/**
	 * Returns true if value is set and is not null.
	 *
	 * @return bool
	 */
	public function isset(): bool
	{
		return isset($this->_value);
	}

	/**
	 * Sets a null or empty value.
	 */
	public function unset(): void
	{
		$this->_value = $this->_allowNull ? null : self::setType('', gettype($this->_value));
	}

	/**
	 * Casts an object to a simpler type.
	 * 
	 * @param  $in
	 *
	 * @return mixed
	 */
	protected static function _castIfObject($in)
	{
		//	This could be any type
		if (is_object($in)) {
			switch (true) {
				case $in instanceof AtomicInterface:
					return $in->get();

				case method_exists($in, '__toString'):
					return $in->__toString();

				case method_exists($in, 'format'):
					return $in->format('c');

				case method_exists($in, 'toArray'):
					return $in->toArray();
			}

			return (array) $in;
		}

		return $in;
	}
}
