<?php
/**
 * Create an array where members must be the same type.
 * @name		TypedArray
 * @copyright	Copyright (c) 2012 Reid Woodbury Jr.
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use ArrayAccess;
use IteratorAggregate;
use ArrayIterator;
use InvalidArgumentException;
use LogicException;
use LengthException;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *	  deep copying of objects is always available.
 */
class TypedArray extends TypedAbstract implements ArrayAccess, IteratorAggregate
{
	/**
	 * An array that contains the items of interest.
	 * @var array
	 */
	private $_container;

	/**
	 * A string that specifies the type of values in the container.
	 * A child class can override _type rather than it being set with the constructor.
	 * @var string|null
	 */
	protected $_type;

	/**
	 * Constructor.
	 *
	 * @param array|object|string|null $values OPTIONAL null
	 * @param string $type OPTIONAL null
	 */
	public function __construct($values = null, $type = null)
	{
		if ( isset($this->_type) ) {
			if ( null !== $type ) {
				throw new LogicException('Can\'t set type when type is set in child class.');
			}
		}
		else {
			if ( '' !== $type && null !== $type ) {
				$this->_type = (string) $type;
			}
			else {
				$this->_type = null;
			}
		}

		$this->_container = [];

		$this->assignObject($values);
	}

	/**
	 * Make sure object is deep copied.
	 */
	public function __clone()
	{
		foreach ($this->_container as $k => $v) {
			if (is_object($v)) {
				$this->_container[$k] = clone $v;
			}
		}
	}

	/**
	 * Copies all members into this class.
	 * This method attempts to coerce all members of the input to the required type.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * Null clears the entire contents of the typed array but not it's type.
	 *
	 * @param object|array|string|null $in OPTIONAL null
	 * @throws InvalidArgumentException
	 */
	public function assignObject($in = null)
	{
		switch ( gettype($in) ) {
			case 'object':
			case 'array':
			break;

			case 'null':
			case 'NULL':
			$this->_container = [];

			return;

			default:
			throw new InvalidArgumentException('unknown input type ' . gettype($in));
		}

		foreach ($in as $k => $v) {
			$this->offsetSet($k, $v);
		}
	}

	/**
	 * Required by the ArrayAccess interface
	 * Coerces input values to be the required type.
	 *
	 * There are 5 basic conditions for $this->_type:
	 * # $this->_type is null (accept any type and value, like a standard array);
	 * # $this->_type is a scalar [bool, int, float, string];
	 * # $this->_type is an array (check if value has toArray);
	 * # $this->_type is an object of type TypedAbstract (call assignObject);
	 * # $this->_type is any other object.
	 *
	 * There are 3 conditions involving $offset:
	 * # $offset is null;
	 * # $offset is set and exists;
	 * # $offset is set and does not exist;
	 *
	 * There are 4 conditions for handling $value:
	 * # $value is null (replace current scalar values with null, reset non-scalars);
	 * # $value is a scalar (cast);
	 * # $value is a an array (check for toArray, or cast);
	 * # $value is a an object (clone if the same as _type, otherwise new _type(value) );
	 *
	 * @param string|int $k
	 * @param mixed $v
	 */
	public function offsetSet($k, $v)
	{
		switch ($this->_type) {
			case 'null':
			case 'NULL':
			case '':
			case null:
			if ( is_object($v) ) {
				$newValue = clone $v;
			}
			else {
				$newValue = $v;
			}
			break;

			case 'bool':
			case 'boolean':
			$newValue = self::_castToBoolean($v);
			break;

			case 'int':
			case 'integer':
			$newValue = self::_castToInteger($v);
			break;

			case 'float':
			case 'double':
			case 'real':
			$newValue = self::_castToDouble($v);
			break;

			case 'string':
			$newValue = self::_castToString($v);
			break;

			case 'array':
			$newValue = self::_castToArray($v);
			break;

			//	All object and class types.
			default:
			if ( null === $k || !isset($this->_container[$k]) || !($this->_container[$k] instanceof TypedAbstract) ) {
				$newValue =
					( is_object($v) && get_class($v) === $this->_type ) ?
						clone $v :
						new $this->_type($v);
			}
			//	Else it's an instance of our special type.
			else {
				$this->_container[$k]->assignObject($v);

				return; //	value already assigned to container
			}
			break;
		}

		if ( null === $k ) {
			$this->_container[] = &$newValue;
		}
		else {
			$this->_container[$k] = &$newValue;
		}
	}

	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 * @return bool
	 */
	public function offsetExists($offset)
	{
		return isset($this->_container[$offset]);
	}

	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 */
	public function offsetUnset($offset)
	{
		unset($this->_container[$offset]);
	}

	/**
	 * Required by the ArrayAccess interface.
	 * Returns reference to offset.
	 *
	 * http://stackoverflow.com/questions/20053269/indirect-modification-of-overloaded-element-of-splfixedarray-has-no-effect
	 *
	 * @param string|int $offset
	 * @return mixed
	 */
	public function &offsetGet($offset)
	{
		return $this->_container[$offset];
	}

	/**
	 * Required by the Countable interface.
	 *
	 * @return int
	 */
	public function count()
	{
		return count($this->_container);
	}

	/**
	 * Required by the IteratorAggregate interface.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator()
	{
		return new ArrayIterator($this->_container);
	}

	/**
	 * Returns an array with all members checked for a "toArray" method so
	 * that any member of type "Typed" will also be returned.
	 * Use "__get" and "__set", or $var[$member] to access individual names.
	 *
	 * @return array
	 */
	public function toArray()
	{
		switch ($this->_type) {
			case 'bool':
			case 'boolean':
			case 'int':
			case 'integer':
			case 'float':
			case 'double':
			case 'real':
			case 'string':
			return $this->_container;
		}

		$arr = [];
		foreach ($this->_container as $k => &$v) {
			if ( is_object($v) ) {
				if ( method_exists($v, 'toArray') ) {
					$arr[$k] = $v->toArray();
				}
				elseif ( method_exists($v, '__toString') ) {
					$arr[$k] = $v->__toString();
				}
				else {
					$arr[$k] = (array) $v;
				}
			}
			else {
				$arr[$k] = $v;
			}
		}

		return $arr;
	}

	/**
	 * Returns array keys.
	 *
	 * @return array
	 */
	public function keys()
	{
		return array_keys( $this->_container );
	}

	/**
	 * Apply new names from input array values.
	 *
	 * @param array $keys
	 * @return TypedArray
	 * @throws LengthException
	 */
	public function combine(array $keys)
	{
		if ( count($keys) !== count($this->_container) ) {
			throw new LengthException('array counts do not match');
		}

		$this->_container = array_combine( $keys, $this->_container );

		return $this;
	}
}
