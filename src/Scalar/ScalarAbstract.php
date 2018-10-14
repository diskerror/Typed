<?php
/**
 * Created by Reid Woodbury.
 * Date: 10/13/18
 */

namespace Diskerror\Typed\Scalar;


/**
 * Class ScalarAbstract
 * @package Diskerror\Typed\Scalar
 */
abstract class ScalarAbstract
{
	/**
	 * Indicates whether or not the value can also be null.
	 * @var bool
	 */
	protected $_allowNull;

	/**
	 * Stores the scalar value.
	 * @var mixed
	 */
	protected $_value;

	/**
	 * ScalarAbstract constructor.
	 *
	 * @param      $in
	 * @param bool $allowNull
	 */
	public function __construct($in, bool $allowNull = false)
	{
		$this->_allowNull = $allowNull;
		$this->set($in);
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
}
