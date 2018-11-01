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
		switch (gettype($in)) {
			case 'object':
				if ($in instanceof ScalarAbstract) {
					$this->_value = $in->get();
					break;
				}
				if ($in instanceof TypedInterface) {
					throw new UnexpectedValueException('you must be kidding');
				}
				if (method_exists($in, '__toString')) {
					$this->_value = $in->__toString();
					break;
				}
				if (method_exists($in, 'format')) {
					$this->_value = $in->format('c');
					break;
				}

				if (method_exists($in, 'toArray')) {
					$in = $in->toArray();
				}
			//	other objects fall through, object to array falls through
			case 'array':
				$jsonStr     = json_encode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				$this->_value = $jsonStr;
			break;

			case 'null':
			case 'NULL':
				$this->_setNullOrDefault();
			break;

			default:
				$this->_value = $in;
		}
	}
}
