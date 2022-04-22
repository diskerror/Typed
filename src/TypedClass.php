<?php
/** @noinspection ALL */
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use DateTimeInterface;
use Diskerror\Typed\Scalar\TAnything;
use Diskerror\Typed\Scalar\TBoolean;
use Diskerror\Typed\Scalar\TFloat;
use Diskerror\Typed\Scalar\TInteger;
use Diskerror\Typed\Scalar\TString;
use InvalidArgumentException;
use Traversable;
use TypeError;

/**
 * Create a child of this class with your named properties with a visibility of
 *      protected or private, and default values of the desired type. Property
 *      names CANNOT begin with an underscore. This maintains the Zend Framework
 *      convention that protected and private property names should begin with an
 *      underscore. This abstract class will expose all members whose name don't
 *      begin with an underscore, but filter access to those class members or
 *      properties that have a visibility of protected or private.
 *
 * Input to the constructor or assign methods must be an array or object. Only the
 *      values in the matching names will be filtered and copied into the object.
 *      All input will be copied by value, not referenced.
 *
 * This class adds simple casting of input values to be the same type as the
 *      named property or member. This includes scalar values, built-in PHP classes,
 *      and other classes, especially those derived from this class.
 *
 * Only properties in the original child class are allowed. This prevents erroneously
 *      adding properties on the fly.
 *
 * More elaborate filtering can be done by creating methods with this naming
 *      convention: If property is called "personName" then create a method called
 *      "_set_personName($in)". That is, prepend "_set_" to the property name.
 *
 * The ideal usage of this abstract class is as the parent class of a data set
 *      where the input to the constructor (or assign) method is an HTTP request
 *      object. It will help with filtering and insuring the existence of default
 *      values for missing input parameters.
 */
abstract class TypedClass extends TypedAbstract
{
	/**
	 * Holds the name pairs for when different/bad key names need to point to the same data.
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Holds the name of the name of the child class for method_exists and property_exists.
	 *
	 * @var string
	 */
	private $_calledClass;

	/**
	 * Holds the default values of the called class to-be-public properties in associative array.
	 *
	 * @var array
	 */
	private $_defaultValues;

	/**
	 * Holds the names of the called class' to-be-public properties in an indexed array.
	 *
	 * @var array
	 */
	private $_publicNames;

	/**
	 * Holds the count of the to-be-public properties.
	 *
	 * @var int
	 */
	private $_count;


	/**
	 * Constructor.
	 * Accepts an object, array, or JSON string.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = null)
	{
		switch (gettype($in)) {
			case 'string':
			case 'array':
			case 'object':
			case 'NULL':
			case 'null':
				break;

			case 'bool':
			case 'boolean':
				if (!$in) {
					break;
				}
			//	"True" falls through and triggers exception.
			//	We allow "false" because some DB frameworks return "false" for empty result sets.

			default:
				throw new InvalidArgumentException('bad value to constructor');
		}

		$this->_initArrayOptions();
		$this->_initMetaData();
		$this->_initProperties();
		$this->replace($in);
	}

	protected function _initMetaData()
	{
		$this->_calledClass = get_called_class();

		//	Build array of default values with converted types.
		//	First, get all class properties then remove elements with names starting with underscore, except "_id".
		$this->_defaultValues = get_class_vars($this->_calledClass);
		foreach ($this->_defaultValues as $k => &$v) {
			if (($k[0] === '_' && $k !== '_id') || $this->_isArrayOption($k)) {
				unset($this->_defaultValues[$k]);
				continue;
			}

			switch (gettype($v)) {
				case 'null':
				case 'NULL':
					$v = new TAnything($v);
					break;

				case 'bool':
				case 'boolean':
					$v = new TBoolean($v);
					break;

				case 'int':
				case 'integer':
					$v = new TInteger($v);
					break;

				case 'float':
				case 'double':
				case 'real':
					$v = new TFloat($v);
					break;

				case 'string':
					$v = new TString($v);
					break;

				case 'array':
					if (!empty($v) && array_values($v) === $v && is_string($v[0]) && class_exists($v[0])) {
						$class = array_shift($v);
						$v     = new $class(...$v);
					}
					else {
						$v = new TypedArray('', $v);
					}
					break;

				default:
					//	Do nothing. Don't try to cast.
			}
		}

		$this->_publicNames = array_keys($this->_defaultValues);
		$this->_count       = count($this->_defaultValues);
	}

	/**
	 * Should be called after _initMetaData().
	 */
	protected function _initProperties()
	{
		foreach ($this->_defaultValues as $k => $v) {
			/**
			 * All properties, except resources, are now objects.
			 * Clone the default/original value back to the original property.
			 */
			if (is_object($v)) {
				$this->{$k} = clone $v;
			}
		}
	}

	/**
	 * Return array of sudo public property names.
	 *
	 * @return array
	 */
	public final function getPublicNames()
	{
		return $this->_publicNames;
	}

	/**
	 * Assign matching values to local keys resetting unmatched local keys.
	 *
	 * Copies all matching property names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $input,
	 *     resets unmatched local properties, and
	 *     skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param $in
	 */
	public function assign($in): void
	{
		$this->_massageInput($in);
		$this->_massageInputArray($in);

		foreach ($this->_publicNames as $publicName) {
			$this->__unset($publicName);
		}

		foreach ($in as $k => $v) {
			$this->_setByName($k, $v);
		}

		$this->_checkRelatedProperties();
	}

	/**
	 * Required method for Countable.
	 *
	 * @return int
	 */
	final public function count(): int
	{
		return $this->_count;
	}

	/**
	 * Required by the IteratorAggregate interface.
	 * Every value is checked for change during iteration.
	 *
	 * @return Traversable
	 */
	public function getIterator(): Traversable
	{
		return (function &() {
			foreach ($this->_defaultValues as $k => $vDefault) {
				if ($vDefault instanceof AtomicInterface) {
					$v = $this->{$k}->get();
					yield $k => $v;
					$this->{$k}->set($v);
				}
				else {
					yield $k => $this->{$k};

					//	Cast if not the same type.
					if (!is_object($this->{$k}) || get_class($this->{$k}) !== get_class($vDefault)) {
						$this->_setByName($k, $this->{$k});
					}
					//	Null property types don't get checked.
				}
			}
		})();
	}

	/**
	 * String representation of PHP object.
	 *
	 * This omits data that is part of the class definition.
	 *
	 * @link  https://www.php.net/manual/en/language.oop5.magic.php#object.serialize
	 * @return ?array
	 */
	public function __serialize(): ?array
	{
		return $this->_toArray($this->serializeOptions);
	}

	/**
	 * Constructs the object from serialized PHP.
	 *
	 * This uses a faster but unsafe restore technique. It assumes that the
	 * serialized data was created by the local serialize method and was
	 * safely stored locally. No type checking is performed on restore. All
	 * data structure members have been serialized so no initialization of
	 * empty need be done.
	 *
	 * @link  https://www.php.net/manual/en/language.oop5.magic.php#object.unserialize
	 *
	 * @param array $data
	 *
	 * @return void
	 */
	public function __unserialize(array $data): void
	{
		$this->_initMetaData();

		foreach ($data as $k => $v) {
			$this->_setByName($k, $v);
		}
	}


	/**
	 * Deep replace local values with matches from input.
	 *
	 * Copies all matching property names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $input,
	 *     leaves unmatched properties in this class untouched, and
	 *     skips names starting with an underscore.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param $in
	 *
	 * @return void
	 */
	public function replace($in): void
	{
		$this->_massageInput($in);
		$this->_massageInputArray($in);

		foreach ($in as $k => $v) {
			if ($this->_keyExists($k)) {
				if ($this->{$k} instanceof TypedAbstract) {
					$this->{$k}->replace($v);
				}
				else {
					$this->_setByName($k, $v);
				}
			}
		}

		$this->_checkRelatedProperties();
	}

	/**
	 * Clone local values and replace matching values with input.
	 *
	 * This method clones $this then replaces matching keys from $in
	 *     and returns the new object.
	 *
	 * @param $in
	 *
	 * @return self
	 */
	public function merge($in): TypedAbstract
	{
		$clone = clone $this;
		$clone->replace($in);

		return $clone;
	}

	/**
	 * @return array
	 */
	protected function _toArray(ArrayOptions $arrayOptions): array
	{
		$keepJsonExpr = $arrayOptions->has(ArrayOptions::KEEP_JSON_EXPR);
		$ZJE_STRING   = '\\Laminas\\Json\\Expr';    //  A string here so library does not need to be included.

		$arr = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);    //  AtomicInterface objects are returned as scalars.

			switch (gettype($v)) {
				case 'resource':
					if (!$arrayOptions->has(ArrayOptions::OMIT_RESOURCE)) {
						$arr[$k] = $v;
					}
					break;

				case 'object':
					if ($this->{$k} instanceof TypedAbstract) {
						$arr[$k] = $v->_toArray($arrayOptions);
					}
					elseif ($this->{$k} instanceof DateTimeInterface) {
						$arr[$k] = $v;    // maintain the type
					}
					elseif (($this->{$k} instanceof $ZJE_STRING) && $keepJsonExpr) {
						$arr[$k] = $v;    // maintain the type
					}
					elseif (method_exists($v, 'toArray')) {
						$arr[$k] = $v->toArray();
					}
					elseif (method_exists($v, '__toString')) {
						$arr[$k] = $v->__toString();
					}
					else {
						$arr[$k] = $v;
					}
					break;

				//	nulls, bools, ints, floats, strings, and arrays
				default:
					$arr[$k] = $v;
			}
		}

		if ($arrayOptions->has(ArrayOptions::OMIT_EMPTY)) {
			foreach ($arr as $k => &$v) {
				if (empty($v) || (is_object($v) && empty((array) $v))) {
					unset($arr[$k]);
				}
			}
		}

		return $arr;
	}

	protected final function _massageInputArray(&$in): void
	{
		//	Test to see if it's an indexed or an associative array.
		//	Leave associative array as is.
		//	Copy indexed array by position to a named array
		if (is_array($in) && !empty($in) && array_values($in) === $in) {
			$newArr   = [];
			$minCount = min(count($in), $this->_count);
			for ($i = 0; $i < $minCount; ++$i) {
				$newArr[$this->_publicNames[$i]] = $in[$i];
			}

			$in = $newArr;
		}
	}

	/**
	 * Sets a variable to its default value rather than unsetting it.
	 *
	 * @param string $k
	 */
	public function __unset(string $k)
	{
		$this->{$k} = clone $this->_defaultValues[$k];
	}

	/**
	 * All member objects will be deep cloned.
	 */
	public function __clone()
	{
		foreach ($this->_publicNames as $k) {
			if (is_object($this->{$k})) {
				$this->{$k} = clone $this->{$k};
			}
		}
	}

	/**
	 * Get variable.
	 *
	 * @param string $k
	 *
	 * @return mixed
	 */
	public function __get(string $k)
	{
		//	Allow reading of array option object.
		if ($this->_isArrayOption($k)) {
			return $this->$k;
		}

		$this->_assertPropName($k);
		return $this->_getByName($k);
	}

	/**
	 * Set variable
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $k
	 * @param mixed $v
	 */
	public function __set($k, $v)
	{
		$this->_assertPropName($k);
		$this->_setByName($k, $v);
		$this->_checkRelatedProperties();
	}

	/**
	 * Is a variable set?
	 *
	 * Behavior for "isset()" expects the variable (property) to exist and not be null.
	 *
	 * @param string $k
	 *
	 * @return bool
	 */
	public function __isset(string $k): bool
	{
		return $this->_keyExists($k) && ($this->{$k} !== null);
	}

	/**
	 * Set data to named variable.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $propName
	 * @param mixed $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName(string $propName, $in)
	{
		if (array_key_exists($propName, $this->_map)) {
			$propName = $this->_map[$propName];
		}

		if (!in_array($propName, $this->_publicNames)) {
			return;
		}

		$setter = '_set_' . $propName;
		if (method_exists($this->_calledClass, $setter)) {
			$this->{$setter}($in);
			return;
		}

		/** All properties are now handled as objects. */
		$propertyDefaultValue = $this->_defaultValues[$propName];

		//	Handle our atomic types.
		if ($propertyDefaultValue instanceof AtomicInterface) {
			$this->{$propName}->set($in);
			return;
		}

		//	Handle our two special object types.
		if ($propertyDefaultValue instanceof TypedAbstract) {
			$this->{$propName}->assign($in);
			return;
		}

		//	Handler for other types of objects.
		$propertyClassType = get_class($propertyDefaultValue);
		switch (gettype($in)) {
			case 'object':
				//	if identical types then reference the original object
				if ($propertyClassType === get_class($in)) {
					$this->{$propName} = $in;
				}
				else {
					//	First try to absorb the input in its entirety,
					try {
						$this->{$propName} = new $propertyClassType($in);
					}
						//	Then try to copy matching members by name.
					catch (TypeError $t) {
						$this->replace($in);
					}
				}
				break;

			case 'null':
			case 'NULL':
				$this->{$propName} = clone $propertyDefaultValue;
				break;

			case 'array':
				if ($propertyClassType === 'stdClass') {
					$this->{$propName} = (object) $in;
					break;
				}
			//	fall through

			default:
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				$this->{$propName} = new $propertyClassType($in);
		}
	}

	/**
	 * Override this method for additional checking such as when a start-date
	 * is required to be earlier than an end-date, any range of values like
	 * minimum and maximum, or any custom filtering dependent on more than a single property.
	 */
	protected function _checkRelatedProperties()
	{
	}

	/**
	 * Throws exception if named property does not exist.
	 *
	 * @param string $k
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _assertPropName(string $k)
	{
		if (!$this->_keyExists($k)) {
			throw new InvalidArgumentException();
		}
	}

	/**
	 * Get variable by name.
	 *
	 * @param string $propName
	 *
	 * @return mixed
	 */
	protected function _getByName(string $propName)
	{
		if (array_key_exists($propName, $this->_map)) {
			$propName = $this->_map[$propName];
		}

		if ($this->{$propName} instanceof AtomicInterface) {
			return $this->{$propName}->get();
		}

		$getter = '_get_' . $propName;
		if (method_exists($this->_calledClass, $getter)) {
			return $this->{$getter}();
		}

		return $this->{$propName};
	}

	/**
	 * Returns true if key/prop name exists or is mappable.
	 *
	 * @param string $propName
	 *
	 * @return bool
	 */
	private function _keyExists(string $propName): bool
	{
		if (array_key_exists($propName, $this->_map)) {
			$propName = $this->_map[$propName];
		}

		return in_array($propName, $this->_publicNames);
	}
}
