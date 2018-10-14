<?php
/**
 * Created by PhpStorm.
 * User: reid
 * Date: 10/13/18
 * Time: 6:20 PM
 */

namespace Diskerror\Typed\Scalar;


class String extends ScalarAbstract
{
	public function set($in)
	{
		switch (gettype($in)) {
			case 'object':
				if (method_exists($in, '__toString')) {
					$in = $in->__toString();
				}
				elseif (method_exists($in, 'format')) {
					$in = $in->format('c');
				}
				elseif (method_exists($in, 'toArray')) {
					$in = $in->toArray();
				}
			//	other objects fall through, object to array falls through
			case 'array':
				$jsonStr     = json_encode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new \UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				$this->_value = $jsonStr;
				break;

			case 'null':
			case 'NULL':
				$this->_value = $this->_allowNull ? null : '';
				break;
		}

		return (string)$in;
	}
}
