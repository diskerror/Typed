<?php
/**
 * Create an array where members must be the same type.
 * @name        TypedArray
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
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
 *      deep copying of objects is always available.
 */
class TypedArray extends TypedAbstract implements ArrayAccess, IteratorAggregate
{
	/**
	 * A string that specifies the type of values in the container.
	 * A child class can override _type rather than it being set with the constructor.
	 * @var string|null
	 */
	protected $_type;

	/**
	 * An array that contains the items of interest.
	 * @var array
	 */
	private $_container;

	/**
	 * Constructor.
	 *
	 * @param array|object|string|null $values OPTIONAL null
	 * @param string                   $type   OPTIONAL null
	 */
	public function __construct($values = null, $type = null)
	{
		if (isset($this->_type)) {
			if (null !== $type) {
				throw new LogicException('Can\'t set type when type is set in child class.');
			}
		}
		else {
			if ('' !== $type && null !== $type) {
				$this->_type = (string)$type;
			}
			else {
				$this->_type = null;
			}
		}

		$this->_container = [];

		$this->assignObject($values);
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
	 *
	 * @throws InvalidArgumentException
	 */
	public function assignObject(&$in = null)
	{
		switch (gettype($in)) {
			case 'object':
			case 'array':
			break;

			case 'null':
			case 'NULL':
				$this->_container = [];
				return;

			default:
				throw new InvalidArgumentException('unknown input type ' . gettype($in) . ', value: "' . $in . '"');
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
	 * # $offset is set and does not exist (null);
	 *
	 * There are 4 conditions for handling $value:
	 * # $value is null (replace current scalar values with null, reset non-scalars);
	 * # $value is a scalar (cast);
	 * # $value is a an array (check for toArray, or cast);
	 * # $value is a an object (clone if the same as _type, otherwise new _type(value) );
	 *
	 * @param string|int $k
	 * @param mixed      $v
	 */
	public function offsetSet($k, $v)
	{
		switch ($this->_type) {
			case 'null':
			case 'NULL':
			case '':
			case null:
				if (is_object($v)) {
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
				if (null === $k || !isset($this->_container[$k]) || !($this->_container[$k] instanceof TypedAbstract)) {
					$newValue =
						(is_object($v) && get_class($v) === $this->_type) ?
							clone $v :
							new $this->_type($v);
				}
				//	Else it is an instance of our special type.
				else {
					$this->_container[$k]->assignObject($v);

					return; //	value already assigned to container
				}
			break;
		}

		if (null === $k) {
			$this->_container[] = &$newValue;
		}
		else {
			$this->_container[$k] = &$newValue;
		}
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
	 *
	 * @return mixed
	 */
	public function &offsetGet($offset)
	{
		if (!$this->offsetExists($offset)) {
			$this->_container[$offset] = new $this->_type();
		}

		return $this->_container[$offset];
	}

	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 *
	 * @return bool
	 */
	public function offsetExists($offset): bool
	{
		return isset($this->_container[$offset]);
	}

	/**
	 * Required by the Countable interface.
	 *
	 * @return int
	 */
	public function count(): int
	{
		return count($this->_container);
	}

	/**
	 * Required by the IteratorAggregate interface.
	 *
	 * @return ArrayIterator
	 */
	public function getIterator(): iterable
	{
		return new ArrayIterator($this->_container);
	}

	/**
	 * Returns array keys.
	 *
	 * @return array
	 */
	public function keys(): array
	{
		return array_keys($this->_container);
	}

	/**
	 * Apply new names from input array values.
	 *
	 * @param array $keys
	 *
	 * @return TypedArray
	 * @throws LengthException
	 */
	public function combine(array $keys): self
	{
		if (count($keys) !== count($this->_container)) {
			throw new LengthException('array counts do not match');
		}

		$this->_container = array_combine($keys, $this->_container);

		return $this;
	}

	/**
	 * Behave like array_shift.
	 *
	 * @return mixed
	 */
	public function shift()
	{
		return array_shift($this->_container);
	}

	/**
	 * Returns an array with all members checked for a "toArray" method so
	 * that any member of type "Typed" will also be returned.
	 * Use "__get" and "__set", or $var[$member] to access individual names.
	 *
	 * @return array
	 */
	public function toArray(): array
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
			case 'array':
			case 'resource':
				return $this->_container;
		}

		//	At this point all items are some type of object.
		$arr = [];
		if (method_exists($this->_type, 'toArray')) {
			foreach ($this->_container as $k => &$v) {
				$arr[$k] = $v->toArray();
			}
		}
		elseif (method_exists($this->_type, '__toString')) {
			foreach ($this->_container as $k => &$v) {
				$arr[$k] = $v->__toString();
			}
		}
		else {
			foreach ($this->_container as $k => &$v) {
				$arr[$k] = (array)$v;
			}
		}

		return $arr;
	}

	/**
	 * This is simmilar to "toArray" above except that some conversions are
	 * made to be more compatible to MongoDB. All objects with a lineage
	 * of DateTime are converted to MongoDB\BSON\UTCDateTime. All times are
	 * assumed to be UTC time. Null or empty members are omitted.
	 *
	 * @param array $opts
	 *
	 * @return array
	 */
	final public function getSpecialObj(array $opts = []): array
	{
		//	Options that are passed in overwrite coded options.
		$opts = array_merge(['dateToBsonDate' => true, 'keepJsonExpr' => true, 'switch_id' => true], $opts);

		$arr = [];
		switch ($this->_type) {
			case 'bool':
			case 'boolean':
			case 'int':
			case 'integer':
			case 'float':
			case 'double':
			case 'real':
			case 'resource':
				foreach ($this->_container as $k => &$v) {
					if ($v !== null) {
						$arr[$k] = $v;
					}
				}

				return $arr;

			case 'string':
				foreach ($this->_container as $k => &$v) {
					if ($v !== '' && $v !== null) {
						$arr[$k] = $v;
					}
				}

				return $arr;

			case 'array':
				foreach ($this->_container as $k => &$v) {
					if (count($v) && $v !== null) {
						$arr[$k] = $v;
					}
				}

				return $arr;
		}

		//	At this point all items are some type of object.
		if (method_exists($this->_type, 'getSpecialObj')) {
			foreach ($this->_container as $k => $v) {
				$tObj = $v->getSpecialObj($opts);
				if (count($tObj)) {
					$arr[$k] = $tObj;
				}
			}
		}
		elseif ($this->_type instanceof \DateTime && $opts['dateToBsonDate']) {
			foreach ($this->_container as $k => $v) {
				$arr[$k] = new \MongoDB\BSON\UTCDateTime($v->getTimestamp() * 1000);
			}
		}
		elseif ($this->_type instanceof \MongoDB\BSON\UTCDateTime && $opts['dateToBsonDate']) {
			foreach ($this->_container as $k => $v) {
				$arr[$k] = $v;
			}
		}
		elseif (method_exists($this->_type, 'toArray')) {
			foreach ($this->_container as $k => $v) {
				$vArr = $v->toArray();
				if (count($vArr)) {
					$arr[$k] = $vArr;
				}
			}
		}
		elseif (method_exists($this->_type, '__toString')) {
			foreach ($this->_container as $k => $v) {
				$vStr = $v->__toString();
				if ($vStr !== '') {
					$arr[$k] = $vStr;
				}
			}
		}
		else {
			foreach ($this->_container as $k => $v) {
				if (count($v)) {
					$arr[$k] = $v;
				}
			}
		}

		if ($opts['switch_id'] && array_key_exists('id_', $arr)) {
			$arr['_id'] = $arr['id_'];
			unset($arr['id_']);
		}

		return $arr;
	}
}
