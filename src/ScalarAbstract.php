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
		if ($in instanceof ScalarAbstract) {
			$this->set($in->get());
		}
		else {
			$this->set($in);
		}
		$this->_defaultValue = $this->_value;
	}

	/**
	 * @param stdClass $in
	 *
	 * @return array
	 */
	protected static function _castObject(stdClass $in): array
	{
		if (method_exists($in, 'toArray')) {
			return $in->toArray();
		}
		return (array)$in;
	}

	/**
	 * Filters the value before saving.
	 */
	abstract public function set($in);

	/**
	 * Returns the scalar value.
	 */
	public function get()
	{
		return $this->_value;
	}

	/**
	 * Returns a null or the default preset value.
	 *
	 * @return mixed
	 */
	protected function _setNullOrDefault()
	{
		$this->_value = $this->_allowNull ? null : $this->_defaultValue;
	}
}
