<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use ArrayAccess;
use LogicException;
use InvalidArgumentException;
use UnexpectedValueException;

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
	 *
	 * @var string|null
	 */
	protected $_type;

	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var ArrayOptions
	 */
	private $_arrayOptions;

	/**
	 * Holds default options for "toArray" customizations.
	 *
	 * @var int
	 */
	protected $_arrayOptionDefaults = 0;

	/**
	 * An array that contains the items of interest.
	 *
	 * @var array
	 */
	private $_container;

	/**
	 * Constructor.
	 *
	 * @param array|object|string|null $values OPTIONAL null
	 * @param string                   $type   OPTIONAL null
	 *
	 * @throws LogicException
	 */
	public function __construct($values = null, string $type = '')
	{
		$this->_arrayOptions = new ArrayOptions($this->_arrayOptionDefaults);

		if (!isset($this->_type)) {
			$this->_type = $type;
		}
		elseif (isset($this->_type) && $type !== '') {
			throw new LogicException('Can\'t set type in constructor when type is set in child class.');
		}

		switch (strtolower($this->_type)) {
			case '':
			case 'null':
			case 'anything':
			case 'scalar':
				$this->_type = SAAnything::class;
			break;

			case 'bool':
			case 'boolean':
				$this->_type = SABoolean::class;
			break;

			case 'int':
			case 'integer':
				$this->_type = SAInteger::class;
			break;

			case 'float':
			case 'double':
			case 'real':
				$this->_type = SAFloat::class;
			break;

			case 'string':
				$this->_type = SABinary::class;
			break;

			case 'array':
				$this->_type = TypedArray::class;
			break;
		}

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
					throw new UnexpectedValueException(json_last_error_msg(), $jsonLastErr);
				}
				if ($in === null) {
					$this->_container = [];    //	remove all current values
					return;
				}
			break;

			case 'null':
			case 'NULL':
				$this->_container = [];    //	remove all current values
				return;

			default:
				throw new InvalidArgumentException('bad input type ' . $inputType . ', value: "' . $in . '"');
		}

		$this->_container = [];

		foreach ($in as $k => $v) {
			$this->offsetSet($k, $v);
		}
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
	 * Return an integer representing the toArray conversion options.
	 *
	 * @return int
	 */
	public function getArrayOptions(): int
	{
		return $this->_arrayOptions->get();
	}

	/**
	 * Takes an integer representing the toArray conversion options.
	 *
	 * @param int $opts
	 */
	public function setArrayOptions(int $opts)
	{
		$this->_arrayOptions->set($opts);
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
		if (is_a($this->_type, ScalarAbstract::class, true)) {
			return (function &() {
				foreach ($this->_container as $k => $v) {
					$v     = $v->get();
					$vOrig = $v;
					yield $k => $v;
					if ($v !== $vOrig) {
						$this->_container[$k]->set($v);
					}
				}
			})();
		}
		else {
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
	 * Be sure json_encode gets our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * String representation of object.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		return serialize([
			'_type' => $this->_type,
			'_arrayOptions' => $this->_arrayOptions,
			'_container' => $this->_container
		]);
	}

	/**
	 * Constructs the object
	 *
	 * @link  https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized The string representation of the object.
	 *
	 * @return void
	 */
	public function unserialize($serialized)
	{
		$data = unserialize($serialized);

		$this->_type         = $data['_type'];
		$this->_arrayOptions = $data['_arrayOptions'];
		$this->_container    = $data['_container'];
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
		$omitID    = $this->_arrayOptions->has(ArrayOptions::OMIT_ID);
		$bsonDate  = $this->_arrayOptions->has(ArrayOptions::TO_BSON_DATE);

		$output = [];

		//	At this point all items are some type of object.
		if (is_a($this->_type, ScalarAbstract::class, true)) {
			foreach ($this->_container as $k => $v) {
				$v = $v->get();
				if (($v !== '' && $v !== null) || !$omitEmpty || ($k === '_id' && !$omitID)) {
					$output[$k] = $v;
				}
			}
		}
		elseif (is_a($this->_type, \DateTime::class, true) && $bsonDate) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = new \MongoDB\BSON\UTCDateTime($v->getTimestamp() * 1000);
			}
		}
		elseif (is_a($this->_type, '\\MongoDB\\BSON\\UTCDateTime', true) && $bsonDate) {
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
				if (($v !== '' && $v !== null) || !$omitEmpty || ($k === '_id' && !$omitID)) {
					$output[$k] = $v;
				}
			}
		}

		return $output;
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
			$this->offsetSet($offset, null);

			//	Returns new offset created by ::offsetSet().
			if (null === $offset) {
				end($this->_container);
				$offset = key($this->_container);
			}
		}

		return $this->_container[$offset];
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
		if (null === $k) {
			$this->_container[] = (is_object($v) && get_class($v) === $this->_type) ? $v : new $this->_type($v);
		}
		elseif (!isset($this->_container[$k])) {
			$this->_container[$k] = (is_object($v) && get_class($v) === $this->_type) ? $v : new $this->_type($v);
		}
		elseif (is_a($this->_type, ScalarAbstract::class, true)) {
			$this->_container[$k]->set($v);
		}
		elseif (is_a($this->_type, TypedInterface::class, true)) {
			$this->_container[$k]->assign($v);
		}
		else {
			$this->_container[$k] = (is_object($v) && get_class($v) === $this->_type) ? $v : new $this->_type($v);
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

	/**
	 * @return array
	 */
	public function getValues()
	{
		return array_values($this->_container);
	}

	/**
	 * @param \Traversable|array $ta
	 */
	public function merge($ta)
	{
		if (is_array($ta) && $ta === array_values($ta)) {
			foreach ($ta as $v) {
				$this->offsetSet(null, $v);
			}
		}
		else {
			foreach ($ta as $k => $v) {
				$this->offsetSet($k, $v);
			}
		}
	}
}
