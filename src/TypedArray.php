<?php
/**
 * Create an array where members must be the same type.
 * @name        TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use ArrayAccess;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray implements TypedInterface, ArrayAccess
{
	/**
	 * A string that specifies the type of values in the container.
	 * A child class can override _type rather than it being set with the constructor.
	 * @var string|null
	 */
	protected $_type;

	/**
	 * Holds options for "toArray" customizations.
	 * @var \Diskerror\Typed\ArrayOptions
	 */
	protected $_arrayOptions;

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
	 *
	 * @throws \LogicException
	 */
	public function __construct($values = null, string $type = null)
	{
		if (!isset($this->_arrayOptions)) {
			$this->_arrayOptions = new ArrayOptions();
		}

		if (isset($this->_type)) {
			if (null !== $type) {
				throw new \LogicException('Can\'t set type when type is set in child class.');
			}
		}
		else {
			$this->_type = ('' === $type || null === $type) ? 'null' : $type;
		}
		$this->_type = $this->_type ? : 'null';    //	If empty then change to "null".

		$this->_container = [];

		$this->assign($values);
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
	 * @throws \InvalidArgumentException
	 */
	public function assign($in = null)
	{
		$inputType = gettype($in);
		switch ($inputType) {
			case 'object':
			case 'array':
				break;

			case 'string':
				$in          = json_decode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new \UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				break;

			case 'null':
			case 'NULL':
				$this->_container = [];    //	remove all current values
				return;

			default:
				throw new \InvalidArgumentException('bad input type ' . $inputType . ', value: "' . $in . '"');
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
	 * # $this->_type is an object of type TypedAbstract (call assign);
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
				if (is_object($v)) {
					$newValue = clone $v;
				}
				else {
					$newValue = $v;
				}
				break;

			case 'bool':
			case 'boolean':
				$newValue = Cast::toBoolean($v);
				break;

			case 'int':
			case 'integer':
				$newValue = Cast::toInteger($v);
				break;

			case 'float':
			case 'double':
			case 'real':
				$newValue = Cast::toDouble($v);
				break;

			case 'string':
				$newValue = Cast::toString($v);
				break;

			case 'array':
				$newValue = Cast::toArray($v);
				break;

			//	All object and class types.
			default:
				if (null === $k || !isset($this->_container[$k]) || !($this->_container[$k] instanceof TypedInterface)) {
					$newValue = (is_object($v) && get_class($v) === $this->_type) ?
						clone $v :
						new $this->_type($v);
				}
				//	Else it is an instance of our special type.
				else {
					$this->_container[$k]->assign($v);

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

	public function getArrayOptions(): int
	{
		return $this->_arrayOptions->get();
	}

	public function setArrayOptions(int $opts)
	{
		$this->_arrayOptions->set($opts);
	}

	/**
	 * Be sure json_encode gets our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
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
		$omitEmpty = $this->_arrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$switchID  = $this->_arrayOptions->has(ArrayOptions::SWITCH_ID);
		$bsonDate  = $this->_arrayOptions->has(ArrayOptions::TO_BSON_DATE);

		$output = [];
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
					if ($v !== null || !$omitEmpty) {
						$output[$k] = $v;
					}
				}

				return $output;

			case 'string':
				foreach ($this->_container as $k => &$v) {
					if (($v !== '' && $v !== null) || !$omitEmpty) {
						$output[$k] = $v;
					}
				}

				return $output;

			case 'array':
				foreach ($this->_container as $k => &$v) {
					if ((count($v) && $v !== null) || !$omitEmpty) {
						$output[$k] = $v;
					}
				}

				return $output;
		}

		$MBDateTime = '\\MongoDB\\BSON\\UTCDateTime';

		//	At this point all items are some type of object.
		if ($this->_type instanceof \DateTime && $bsonDate) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = new \MongoDB\BSON\UTCDateTime($v->getTimestamp() * 1000);
			}
		}
		elseif ($this->_type instanceof $MBDateTime && $bsonDate) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v;
			}
		}
		elseif (method_exists($this->_type, 'toArray')) {
			foreach ($this->_container as $k => $v) {
				if (method_exists($v, 'getArrayOptions')) {
					$vOrigOpts = $v->getArrayOptions();
					$v->setArrayOptions($this->_arrayOptions->get());
				}

				$output[$k] = $v->toArray();

				if (isset($vOrigOpts)) {
					$v->setArrayOptions($vOrigOpts);
					unset($vOrigOpts);
				}

				if (count($output[$k]) === 0 && $omitEmpty) {
					unset($output[$k]);
				}
			}
		}
		elseif (method_exists($this->_type, '__toString')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->__toString();
				if ($output[$k] === '' && $omitEmpty) {
					unset($output[$k]);
				}
			}
		}
		else {
			//	else this is some generic object then copy non-null/non-empty members or properties
			foreach ($this->_container as $k => $v) {
				if (($v !== null && $v !== '') || !$omitEmpty) {
					$output[$k] = $v;
				}
			}
		}

		if ($switchID && array_key_exists('id_', $output)) {
			$output['_id'] = &$output['id_'];
			unset($output['id_']);
		}

		return $output;
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
			//	Be sure offset exists before accessing it.
			switch ($this->_type) {
				case 'bool':
				case 'boolean':    //	'' -> false
				case 'int':
				case 'integer':    //	'' -> 0
				case 'float':
				case 'double':
				case 'real':       //	'' -> 0.0
				case 'string':     //	'' -> ''
					//	We don't need the value that this sets into the container,
					//		but do we need the good offset created by this for scalars?
					$this->offsetSet($offset, '');
					break;

				default:    //	arrays or objects
					$this->offsetSet($offset, null);
					break;

			}

			//	Returns new offset created by ::offsetSet().
			if (null === $offset) {
				end($this->_container);
				$offset = key($this->_container);
			}
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

	public function &getContainerReference()
	{
		return $this->_container;
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
	 * Every value is checked for change during iteration.
	 *
	 * The _type or gettype() can return different names for the same type,
	 * ie. "bool" or "boolean", so we're only going to check the first 3 characters.
	 *
	 * @return \Traversable
	 */
	public function getIterator(): \Traversable
	{
		$thisType3 = substr($this->_type, 0, 3);

		switch ($thisType3) {
			case 'nul':
			case 'NUL':
				//	It can be anything. Don't check it.
				return (function &() {
					foreach ($this->_container as $k => &$v) {
						yield $k => $v;
					}
				})();

			case 'boo':
			case 'int':
			case 'flo':
			case 'dou':
			case 'rea':
			case 'str':
			case 'arr':
			case 'res':
				return (function &() use ($thisType3) {
					foreach ($this->_container as $k => &$v) {
						yield $k => $v;

						//	Cast if not the same type.
						if (substr(gettype($v), 0, 3) !== $thisType3) {
							$this->offsetSet($k, $v);
						}
					}
				})();

			default:
				return (function &() {
					foreach ($this->_container as $k => &$v) {
						yield $k => $v;

						//	Compare whole class names.
						if (get_class($v) !== $this->_type) {
							$this->offsetSet($k, $v);
						}
					}
				})();
		}
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
	 * @throws \LengthException
	 */
	public function combine(array $keys): self
	{
		if (count($keys) !== count($this->_container)) {
			throw new \LengthException('array counts do not match');
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
}
