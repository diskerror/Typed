<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        ScalarAbstract
 * @copyright   Copyright (c) 2018 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use ErrorException;

/**
 * Class ScalarAbstract
 *
 * @package Diskerror\Typed\ScalarAbstract
 */
abstract class ScalarAbstract implements AtomicInterface
{
	/**
	 * Stores the scalar value.
	 * Initialization defaults to false, zero, or an empty string.
	 *
	 * @var mixed
	 */
	protected $_value;

	/**
	 * Indicates whether the value can also be null.
	 * Initialization defaults to false.
	 *
	 * @var bool
	 */
	private bool $_allowNull;

	/**
	 * ScalarAbstract constructor.
	 *
	 * @param mixed $in An empty string will cast to false or zero as needed.
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
	 * Returns true if value is not null.
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
		if ($this->_allowNull) {
			$this->_value = null;
		}
		else {
			$this->_value = self::setType('', gettype($this->_value));
		}
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

			return (array)$in;
		}

		return $in;
	}

	/**
	 * SetType.
	 *
	 * This differs from settype() in that it returns an empty array for an empty string.
	 * It also throws an exception for bad type names.
	 *
	 * @param        $val
	 * @param string $type
	 *
	 * @return array|bool|float|int|string|null
	 * @throws ErrorException
	 */
	public static function setType($val, string $type): mixed
	{
		if ($type === 'array' && $val === '') {
			return [];
		}

		if (settype($val, $type) === false) {
			throw new ErrorException('bad type name');
		}

		return $val;
	}
}
