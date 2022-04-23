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
use DateTimeInterface;
use InvalidArgumentException;
use LengthException;
use Traversable;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends TypedAbstract implements ArrayAccess
{
	/**
	 * A string that specifies the type of values in the container.
	 * A child class can override _type rather than it being set with the constructor.
	 *
	 * @var string
	 */
	protected string $_type = '';

	/**
	 * An array that contains the items of interest.
	 *
	 * @var array
	 */
	protected array $_container = [];

	/**
	 * Constructor.
	 *
	 * If this class is instantiated directly, ie. "$a = new TypedArray('integer', [1, 2, 3]);",
	 * then $param1 must be the data type as a string, and then $param2 can be the initial data.
	 *
	 * If a derived class is instantiated then the data type must be contained in
	 * the class, ie. "protected $_type = 'integer';", and $param1 can be the initial data.
	 *
	 * @param mixed $param1 OPTIONAL ""
	 * @param array|object|null $param2 OPTIONAL null
	 *
	 * @throws InvalidArgumentException
	 */
	public function __construct($param1 = null, $param2 = null)
	{
		$this->_initToArrayOptions();

		if (get_called_class() === self::class) {
			$this->_type = is_string($param1) ? $param1 : '';
			$this->replace($param2);
		}
		else {
			if (null !== $param2) {
				throw new InvalidArgumentException('Only the first parameter can be set when using a derived class.');
			}

			$this->replace($param1);
		}
	}

	/**
	 * Copies all members into this class, removing all existing values.
	 *
	 * Null clears the entire contents of the typed array but not it's type.
	 *
	 * @param $in
	 */
	public function assign($in): void
	{
		$this->_massageInput($in);

		$this->_container = [];    //	initialize array or remove all current values

		foreach ($in as $k => $v) {
			$this->offsetSet($k, $v);
		}
	}

	/**
	 * Copies all members into this class. Indexed keys will be re-indexed.
	 *
	 * @param object|array|string|null $in
	 */
	public function replace($in): void
	{
		$this->_massageInput($in);

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
	 * Required by the IteratorAggregate interface.
	 * Every value is checked for change during iteration.
	 *
	 * This will return a reference to a scalar value, even if it has a
	 * wrapper with the interface AtomicInterface.
	 *
	 * @return Traversable
	 */
	public function getIterator(): Traversable
	{
		if (is_a($this->_type, AtomicInterface::class, true)) {
			return (function &() {
				foreach ($this->_container as $k => $v) {
					$v = $v->get();
					yield $k => $v;
					$this->_container[$k]->set($v);
				}
			})();
		}
		else {
			return (function &() {
				foreach ($this->_container as $k => $v) {
					yield $k => $v;

					//	Compare whole class names.
					//	TODO: This doesn't test enough conditions.
					$isObj = is_object($v);
					if (($isObj && get_class($v) !== $this->_type) || (!$isObj && gettype($v) !== $this->_type)) {
						$this->offsetSet($k, $v);
					}
				}
			})();
		}
	}

	/**
	 * Get string representation of object.
	 *
	 * @link  https://www.php.net/manual/en/language.oop5.magic.php#object.serialize
	 * @return ?array the string representation of the object or null
	 */
	public function __serialize(): ?array
	{
		$ret               = parent::__serialize();
		$ret['_type']      = $this->_type;
		$ret['_container'] = $this->_container;
		return $ret;
	}

	/**
	 * Constructs the object
	 *
	 * @link  https://www.php.net/manual/en/language.oop5.magic.php#object.unserialize
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void
	{
		parent::__unserialize($data);
		$this->_type      = $data['_type'];
		$this->_container = $data['_container'];
	}

	/**
	 * Returns an array with all members checked for a "toArray" method so
	 * that any member of type "Typed" will also be returned.
	 * Use "__get" and "__set", or $var[$member] to access individual names.
	 *
	 * @param ArrayOptions $arrayOptions
	 *
	 * @return array
	 */
	protected function _toArray(ArrayOptions $arrayOptions): array
	{
		$output = [];

		//	TODO: check special case for empty type
		if ($this->_type === '' || self::_isNonObject($this->_type)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v;
			}
		}
		elseif (is_a($this->_type, AtomicInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->get();
			}
		}
		elseif (is_a($this->_type, TypedAbstract::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->_toArray($arrayOptions);    //	can this happen? no...?
			}
		}
		elseif (is_a($this->_type, DateTimeInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->format(DateTime::MYSQL_STRING_IO_FORMAT_MICRO);
			}
		}
		elseif (method_exists($this->_type, 'toArray')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->toArray();
			}
		}
		elseif (method_exists($this->_type, '__toString')) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->__toString();
			}
		}
		else {
			//	else this is an array of some generic objects
			foreach ($this->_container as $k => $v) {
				$output[$k] = (array) $v;
			}
		}

		if ($arrayOptions->has(ArrayOptions::OMIT_EMPTY)) {
			//	Is this an indexed array (not associative)?
			$isIndexed = (array_values($output) === $output);

			//	Remove empty items.
			foreach ($output as $k => $v) {
				if (empty($v)) {
					unset($output[$k]);
				}
			}

			//	If it's an indexed array then fix the indexes.
			if ($isIndexed) {
				$output = array_values($output);
			}
		}

		return $output;
	}

	/**
	 * Merge input with clone of this and return new TypedArray.
	 *
	 * Similar to the function array_merge().
	 *
	 * @param  $in
	 *
	 * @return TypedArray
	 */
	public function merge($in): TypedArray
	{
		$this->_massageInput($in);

		$ret = clone $this;

		foreach ($in as $k => $v) {
			if (is_int($k)) {
				$ret[] = $v;
			}
			else {
				$ret[$k] = $v;
			}
		}

		return $ret;
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
		return array_key_exists($offset, $this->_container);
	}

	/**
	 * Required by the ArrayAccess interface.
	 * Returns value at offset.
	 *
	 * This method cannot return a reference since a reference might contain a scalar
	 * wrapper. We want the scalar, not the wrapper. Objects are always passed by reference.
	 *
	 * Using a reference is handled in getIterator.
	 *
	 * http://stackoverflow.com/questions/20053269/indirect-modification-of-overloaded-element-of-splfixedarray-has-no-effect
	 *
	 * @param string|int $offset
	 *
	 * @return mixed
	 */
	public function offsetGet($offset)
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

		if (is_a($this->_type, AtomicInterface::class, true)) {
			return $this->_container[$offset]->get();
		}

		return $this->_container[$offset];
	}

	/**
	 * Required by the ArrayAccess interface
	 * Coerces input values to be the required type.
	 *
	 * There are 5 basic conditions for $this->_type:
	 * * $this->_type is null (accept any type and value, like a standard array);
	 * * $this->_type is a scalar wrapper [bool, int, float, string];
	 * * $this->_type is an array (check if input value is an object and has toArray);
	 * * $this->_type is an object of type TypedAbstract (call replace());
	 * * $this->_type is any other object.
	 *
	 * There are 3 conditions involving $offset:
	 * * $offset is null;
	 * * $offset is set and does not exist (null);
	 * * $offset is set and exists;
	 *
	 * There are 4 conditions for handling $value:
	 * * $value is null (replace current scalar values with null, reset non-scalars);
	 * * $value is a scalar (cast);
	 * * $value is a an array (check for toArray, or cast);
	 * * $value is a an object (clone if the same as _type, otherwise new _type(value) );
	 *
	 * @param string|int $k
	 * @param mixed $v
	 */
	public function offsetSet($k, $v): void
	{
		if (self::_setBasicTypeAndConfirm($v, $this->_type)) {
			$this->_offsetSet($k, $v);
			return;
		}

		if (is_a($this->_type, AtomicInterface::class, true)) {
			if (!isset($this->_container[$k])) {
				$this->_container[$k] = new $this->_type($v);
			}
			else {
				$this->_container[$k]->set($v);
			}
			return;
		}

		if (is_a($this->_type, TypedAbstract::class, true)) {
			if (!isset($this->_container[$k])) {
				$this->_container[$k] = new $this->_type($v);
			}
			else {
				$this->_container[$k]->replace($v);
			}
			return;
		}

		// if the object types match then
		if (is_object($v) && get_class($v) === $this->_type) {
			$this->_offsetSet($k, $v);
			return;
		}

		$this->_offsetSet($k, new $this->_type($v));
	}

	private function _offsetSet($k, $v)
	{
		if (null === $k) {
			$this->_container[] = $v;
		}
		else {
			$this->_container[$k] = $v;
		}
	}

	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 */
	public function offsetUnset($offset): void
	{
		unset($this->_container[$offset]);
	}

	/**
	 * Make sure object is deep copied.
	 */
	public function __clone()
	{
		if ($this->_type === '') {
			foreach ($this->_container as &$v) {
				// container accepts anything so test each value
				if (is_object($v)) {
					$v = clone $v;
				}
			}
		}
		elseif (!self::_isNonObject($this->_type)) {
			// If not assignable then these must all already be objects.
			foreach ($this->_container as &$v) {
				$v = clone $v;
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
	 * @return array
	 */
	public function values(): array
	{
		return array_values($this->_container);
	}
}
