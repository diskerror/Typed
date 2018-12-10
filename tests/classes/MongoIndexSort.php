<?php
/**
 * Created by PhpStorm.
 * User: 3525339
 * Date: 12/10/2018
 * Time: 2:59 PM
 */

class MongoIndexSort extends \Diskerror\Typed\SAInteger
{
	/**
	 * MongoIndexSort constructor.
	 *
	 * @param mixed $in
	 * @param bool  $allowNull
	 */
	public function __construct($in = 1, bool $allowNull = false)
	{
		parent::__construct($in, $allowNull);
	}

	public function set($in)
	{
		parent::set($in);

		$this->_value = $this->_value > 0 ? 1 : -1;
	}

}
