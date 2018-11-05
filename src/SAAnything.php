<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;

use UnexpectedValueException;

/**
 * Class SAAnything
 *
 * This class allows input to be any scalar.
 * Some objects and arrays will be cast to a string.
 *
 * @package Diskerror\Typed
 */
class SAAnything extends ScalarAbstract
{
	public function set($in)
	{
		if (is_null($in)) {
			$this->_setNullOrDefault();
		}
		else {
			$this->_value = $in;
		}
	}
}
