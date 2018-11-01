<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed;


/**
 * Class SAAnything
 *
 * This class allows input to be any scalar.
 *
 * @package Diskerror\Typed
 */
class SAAnything extends ScalarAbstract
{
	public function set($in)
	{
		$this->_value = $in;
	}
}
