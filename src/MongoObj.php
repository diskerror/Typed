<?php
/**
 * Converts the object or array into something suitable for the querks of MongoDB.
 * @name		SqlStatement
 * @copyright	Copyright (c) 2016 Reid Woodbury Jr.
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use InvalidArgumentException;

/**
 * Converts the object or array into something suitable for the querks of MongoDB.
 */
class MongoObj extends DbGenAbstract
{

	/**
	 * Accepts an object or an associative array.
	 * Objects or arrays are reduced to only having public values.
	 *
	 * @param mixed $in
	 * @throws InvalidArgumentException
	 */
	public function setInput($in)
	{
		if ( is_object($in) ) {
			$this->_input = method_exists($in, 'toArray') ?
				$in->toArray() :
				(array) $in;
		}
		elseif ( !is_array($in) ) {
			throw new InvalidArgumentException('input must be an array or an object');
		}
		else {
			$this->_input = $in;
		}

		//	Simplify structure.
		self::_reducer($this->_input);

		//	Create MongoDB style primary key from "Typed" style name.
		if ( array_key_exists('id_', $this->_input) && is_scalar($this->_input['id_']) ) {
			$id = $this->_input['id_'];
			unset($this->_input['id_']);
			$this->_input = ['_id'=>$id] + $this->_input;
		}
	}

	/**
	 * Returns an [associative] array reduced to an efficient size for MongoDB.
	 *
	 * @return array
	 */
	public function getArray()
	{
	    return $this->_input;
	}

	/**
	 * Recursively step through array to throw out or reduce unwanted members.
	 */
	protected static function _reducer(&$arr)
	{
		foreach ( $arr as $k => &$v ) {
			switch ( gettype($v) ) {
				case 'null':
				case 'NULL':
				case 'resource':
				unset($arr[$k]);
				break;

				case 'string':
				if ( '' === $v ) {
					unset($arr[$k]); //	should this only apply to nulls?
				}
				break;

				case 'object':
				$v = method_exists($v, 'toArray') ? $v->toArray() : (array) $v;
				// fall through

				case 'array':
				self::_reducer($v);

				if ( count($v) === 0 ) {
					unset($arr[$k]);
				}
				break;
			}
		}
	}

}
