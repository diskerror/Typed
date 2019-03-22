<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        \Diskerror\Typed\TypedArray
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
	 * @var string|null
	 */
	protected $_type;

	/**
	 * An array that contains the items of interest.
	 *
	 * @var array
	 */
	protected $_container;

	/**
	 * Constructor.
	 *
	 * This allows for initial values to be passed in the first parameter.
	 * This is only works for children of this class where the type has already
	 * been set.
	 *
	 * @param mixed             $type   OPTIONAL ''
	 * @param array|object|null $values OPTIONAL null
	 */
	public function __construct($type = '', $values = null)
	{
		if (get_called_class() !== self::class) {
			if (!isset($this->_type)) {
				throw new InvalidArgumentException('$this->_type must be set in child class.');
			}

			if (null !== $values) {
				throw new InvalidArgumentException('Only the first parameter can be set when using an inherited class.');
			}

			// Assume the first parameter holds the values and the second is unset.
			$values = $type;
		}
		else {
			$this->_type = $type;
		}

		$this->_initArrayOptions();

		switch (strtolower($this->_type)) {
			case '':
			case 'null':
			case 'anything':
				$this->_type = SAAnything::class;
				break;

			case 'scalar':
				$this->_type = SAScalar::class;
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
				$this->_type = SAString::class;
				break;

			case 'array':
				$this->_type = TypedArray::class;
				break;

			case 'date':
				$this->_type = Date::class;
				break;

			case 'datetime':
				$this->_type = DateTime::class;
				break;
		}

		$this->assign($values);
	}

	/**
	 * Check if the input data is good or needs to be massaged.
	 *
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 * property (property names not starting with an underscore). Extra values
	 * are ignored. Unused properties are unchanged.
	 *
	 * @param $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _massageBlockInput(&$in)
	{
		if (is_string($in)) {
			$in          = json_decode($in);
			$jsonLastErr = json_last_error();
			if ($jsonLastErr !== JSON_ERROR_NONE) {
				throw new InvalidArgumentException(
					'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
					$jsonLastErr
				);
			}
		}

		switch (gettype($in)) {
			case 'object':
			case 'array':
				break;

			case 'null':
			case 'NULL':
				$in = [];
				break;

			case 'bool':
			case 'boolean':
				// A 'false' is returned by MySQL:PDO for "no results".
				if (true !== $in) {
					/** Change false to empty array. */
					$in = [];
				}
			//	A boolean 'true' falls through.

			default:
				throw new InvalidArgumentException('bad input type ' . gettype($in) . ', value: "' . $in . '"');
		}
	}

	/**
	 * Copies all members into this class, removing all existing values.
	 *
	 * Null clears the entire contents of the typed array but not it's type.
	 *
	 * @param object|array|string|null $in
	 *
	 * @throws \InvalidArgumentException
	 */
	public function assign($in)
	{
		$this->_massageBlockInput($in);

		$this->_container = [];    //	initialize array or remove all current values

		foreach ($in as $k => $v) {
			$this->offsetSet($k, $v);
		}
	}

	/**
	 * Copies all members into this class. Indexed keys will be re-indexed.
	 *
	 * @param object|array|string|null $in
	 *
	 * @throws \InvalidArgumentException
	 */
	public function replace($in)
	{
		$this->_massageBlockInput($in);

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
	 * @return Traversable
	 */
	public function getIterator(): Traversable
	{
		if (is_a($this->_type, AtomicInterface::class, true)) {
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
	 * String representation of object.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		return serialize([
			'_type'         => $this->_type,
			'_arrayOptions' => $this->_arrayOptions,
			'_jsonOptions'  => $this->_jsonOptions,
			'_container'    => $this->_container,
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
		$this->_jsonOptions  = $data['_jsonOptions'];
		$this->_container    = $data['_container'];
	}

	/**
	 * Returns an array with all members checked for a "toArray" method so
	 * that any member of type "Typed" will also be returned.
	 * Use "__get" and "__set", or $var[$member] to access individual names.
	 *
	 * @return array
	 */
	protected function _toArray(ArrayOptions $arrayOptions): array
	{
		$omitEmpty = $arrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$output    = [];

		//	At this point all items are some type of object.
		if (is_a($this->_type, AtomicInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->get();
			}
		}
		elseif (is_a($this->_type, TypedAbstract::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v->_toArray($arrayOptions);
			}
		}
		elseif (is_a($this->_type, DateTimeInterface::class, true)) {
			foreach ($this->_container as $k => $v) {
				$output[$k] = $v;
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
				$output[$k] = $v;
			}
		}

		if ($omitEmpty) {
			//	Is this an indexed array (not associative)?
			$isIndexed = (array_values($output) === $output);

			//	Remove empty items.
			foreach ($output as $k => $v) {
				if (empty($v) || (is_object($v) && empty((array)$v))) {
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
	 * @param Traversable|array $ta
	 *
	 * @return \Diskerror\Typed\TypedArray
	 */
	public function merge($ta): self
	{
		$this->_massageBlockInput($in);

		$ret = clone $this;

		foreach ($ta as $k => $v) {
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
	 * # $this->_type is an array (check if input value is an object and has toArray);
	 * # $this->_type is an object of type TypedAbstract (call replace());
	 * # $this->_type is any other object.
	 *
	 * There are 3 conditions involving $offset:
	 * # $offset is null;
	 * # $offset is set and does not exist (null);
	 * # $offset is set and exists;
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
		if (null === $k || !array_key_exists($k, $this->_container)) {
			$v = (is_object($v) && get_class($v) === $this->_type) ? $v : new $this->_type($v);

			if (null === $k) {
				$this->_container[] = $v;
			}
			else {
				$this->_container[$k] = $v;
			}

			return;
		}

		if (is_a($this->_type, AtomicInterface::class, true)) {
			$this->_container[$k]->set($v);
			return;
		}

		if (is_a($this->_type, TypedAbstract::class, true)) {
			$this->_container[$k]->replace($v);
			return;
		}

		$this->_container[$k] = new $this->_type($v);
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
	public function getValues()
	{
		return array_values($this->_container);
	}
}
