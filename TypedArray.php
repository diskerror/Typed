<?php

namespace Typed;

require_once 'TypedInterface.php';

use Zend_Json;

use ArrayAccess, Countable, IteratorAggregate;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *    features of deep copying are available.
 */
class TypedArray implements TypedInterface, ArrayAccess, Countable, IteratorAggregate
{
	/**
	 * An array that contains the items of interest.
	 * @var array
	 */
	private $_container = null;

	/**
	 * A string that specifies the type of values in the container.
	 * A child class can override _type rather than it being set with the constructor.
	 * @var string|null
	 */
	protected $_type = null;

	/**
	 * Constructor.
	 *
	 * @param array|object|string|null $values OPTIONAL null
	 * @param string $type OPTIONAL null
	 */
	public function __construct($values = null, $type = null)
	{
		//	If null or empty then leave _type alone.
		if ( '' !== $type && null !== $type ) {
			$this->_type = (string) $type;
		}

		$this->assignObject($values);
	}

	/**
	 * Make sure object is deep copied.
	 */
	public function __clone()
	{
		foreach ($this->_container as $k=>$v) {
			if (is_object($v)) {
				$this->_container[$k] = clone $v;
			}
		}
	}

	/**
	 * Copies all members into this class.
	 * This method attempts to coerce all members of the input to the required type.
	 *
	 * Input can be an object, an associative array, or
	 *	 a JSON string representing an object.
	 *
	 * Null clears the entire contents of the typed array but not it's type.
	 *
	 * @param object|array|string|null $in OPTIONAL null
	 * @throws UnexpectedValueException
	 */
	public function assignObject($in=null)
	{
		switch ( gettype($in) ) {
			case 'object':
			case 'array':
			break;

			case 'string':
			require_once 'Zend/Json.php';
			//	Zend_Json throws an exception if input cannot be interpreted as a JSON string.
			$in = Zend_Json::decode( $in, Zend_Json::TYPE_ARRAY );
			if ( !is_array($in) ) {
				$in = [];
			}
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
	 * Coerces all input values to be the required type.
	 *
	 * There are 4 conditions involving $offset:
	 *    $offset is null;
	 *    $this->_container[$offset] does not exist;
	 *    $this->_container[$offset] exists but the contents is null;
	 *    $this->_container[$offset] has a current value.
	 * Some container types will allow these conditions to be grouped together.
	 *
	 * There are 6 conditions for handling $value:
	 *    $value is null (replace current value with null);
	 *    $value and _type are both scalars (cast and replace current value);
	 *    $value is a an array or object, and _type is a scalar (throw exception);
	 *    $value is a scalar and _type is an array or object (wrap and save value? throw exception for now);
	 *    $value is an object and _type is an array (cast object to array);
	 *    $value and _type are both exactly the same object or array type (replace current value);
	 *    $value is a simple array or object of name/value pairs and _type implements TypedInterface.
	 * Each scalar type must be handled separately due to the way PHP handles casting.
	 *
	 * @param string|int $offset
	 * @param mixed $value
	 * @throws LogicException
	 */
	final public function offsetSet($offset, $value)
	{
		switch ($this->_type) {
			case 'null':
			case 'NULL':
			case null:
			if ( null === $offset ) {
				$this->_container[] = $value;
			}
			else {
				$this->_container[$offset] = $value;
			}
			break;


			case 'bool':
			case 'boolean':
			if ( !is_scalar($value) ) {
				throw new LogicException('A scalar type is expected.');
			}
			elseif ( null === $offset ) {
				$this->_container[] = (null === $value ? null : (boolean) $value);
			}
			else {
				$this->_container[$offset] = (null === $value ? null : (boolean) $value);
			}
			break;


			case 'int':
			case 'integer':
			if ( !is_scalar($value) ) {
				throw new LogicException('A scalar type is expected.');
			}
			elseif ( null === $offset ) {
				$this->_container[] = (null === $value ? null : (int) $value);
			}
			else {
				$this->_container[$offset] = (null === $value ? null : (int) $value);
			}
			break;


			case 'float':
			case 'double':
			case 'real':
			if ( !is_scalar($value) ) {
				throw new LogicException('A scalar type is expected.');
			}
			elseif ( null === $offset ) {
				$this->_container[] = (null === $value ? null : (double) $value);
			}
			else {
				$this->_container[$offset] = (null === $value ? null : (double) $value);
			}
			break;


			case 'string':
			if ( !is_scalar($value) ) {
				throw new LogicException('A scalar type is expected.');
			}
			elseif ( null === $offset ) {
				$this->_container[] = (null === $value ? null : (string) $value);
			}
			else {
				$this->_container[$offset] = (null === $value ? null : (string) $value);
			}
			break;


			case 'array':
			if ( is_scalar($value) ) {
				throw new LogicException('An array or object type is expected.');
			}
			elseif ( null === $offset ) {
				$this->_container[] =
					( is_object($value) && method_exists($value, 'toArray') ) ?
						$value->toArray() :
						(null === $value ? [] : (array) $value);
			}
			else {
				$this->_container[$offset] =
					( is_object($value) && method_exists($value, 'toArray') ) ?
						$value->toArray() :
						(null === $value ? [] : (array) $value);
			}
			break;


			//	All object and class types.
			default:
			if ( null === $offset ) {
				$this->_container[] =
					( is_object($value) && get_class($value) === $this->_type ) ?
						clone $value :
						new $this->_type($value);
			}
			//	If location is not set or is null, AND NOT our special TypedAbstract class then just overwrite.
			elseif ( !isset($this->_container[$offset]) || !is_subclass_of($this->_type, 'TypedInterface') ) {
				$this->_container[$offset] =
					( is_object($value) && get_class($value) === $this->_type ) ?
						clone $value :
						new $this->_type($value);
			}
			//	Classes that implement TypedInterface handle the input type.
			else {
				$this->_container[$offset]->assignObject($value);
			}
			break;
		}
	}


	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 * @return bool
	 */
	final public function offsetExists($offset)
	{
		return isset($this->_container[$offset]);
	}

	/**
	 * Required by the ArrayAccess interface.
	 *
	 * @param string|int $offset
	 */
	final public function offsetUnset($offset)
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
	final public function &offsetGet($offset)
	{
		return $this->_container[$offset];
	}

	/**
	 * Required by the Countable interface.
	 *
	 * @return int
	 */
	final public function count()
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
	final public function toArray()
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
		foreach ($this->_container as $k=>&$v) {
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
	final public function keys()
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
	final public function combine(array $keys)
	{
		if ( count($keys) !== count($this->_container) ) {
			throw new LengthException('array counts do not match');
		}

		$this->_container = array_combine( $keys, $this->_container );

		return $this;
	}

	/**
	 * Returns JSON string representing the object.
	 * Optionally retruns a pretty-print string.
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	final public function toJson($pretty = false)
	{
		$j = Zend_Json::encode( $this->toArray() );

		if ( $pretty ) {
			return Zend_Json::prettyprint( $j );
		}

		return $j;
	}
}
