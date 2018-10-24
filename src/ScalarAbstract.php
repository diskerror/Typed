<?php
/**
 * Created by Reid Woodbury.
 * Date: 10/13/18
 */

namespace Diskerror\Typed;

use stdClass;


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
	 * @param mixed $in A default of an empty string will cast to false or zero as needed.
	 * @param bool  $allowNull
	 */
	public function __construct($in = '', bool $allowNull = false)
	{
		$this->_allowNull = $allowNull;
		$this->set($in);
	}

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
}
