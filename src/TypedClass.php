<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           \Diskerror\Typed\TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use InvalidArgumentException;
use MongoDB\BSON\ObjectId;
use Traversable;

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
 * This class will adds simple casting of input values to be the same type as the
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
				break;

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

	private function _initMetaData()
	{
		$this->_calledClass = get_called_class();

		//	Build array of default values with converted types.
		//	First get all class properties then remove elements with names starting with underscore, except "_id".
		$this->_defaultValues = get_class_vars($this->_calledClass);
		foreach ($this->_defaultValues as $k => &$v) {
			if ($k[0] === '_' && $k !== '_id') {
				unset($this->_defaultValues[$k]);
				continue;
			}

			switch (gettype($v)) {
				case 'null':
				case 'NULL':
					$v = new SAAnything($v);
					break;

				case 'bool':
				case 'boolean':
					$v = new SABoolean($v);
					break;

				case 'int':
				case 'integer':
					$v = new SAInteger($v);
					break;

				case 'float':
				case 'double':
				case 'real':
					$v = new SAFloat($v);
					break;

				case 'string':
					$v = new SAString($v);
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
	private function _initProperties()
	{
		foreach ($this->_defaultValues as $k => &$v) {
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
	 * @param object|array|string|bool|null $in -OPTIONAL
	 */
	public function assign($in)
	{
		$this->_massageBlockInput($in);

		if (empty($in)) {
			foreach ($this->_publicNames as $publicName) {
				$this->__unset($publicName);
			}
		}
		elseif (is_object($in)) {
			foreach ($this->_publicNames as $publicName) {
				if (isset($in->{$publicName})) {
					$this->_setByName($publicName, $in->{$publicName});
				}
				else {
					$this->__unset($publicName);
				}
			}
		}
		else {
			foreach ($this->_publicNames as $publicName) {
				if (isset($in[$publicName])) {
					$this->_setByName($publicName, $in[$publicName]);
				}
				else {
					$this->__unset($publicName);
				}
			}
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
	 * @return \Traversable
	 */
	public function getIterator(): Traversable
	{
		return (function &() {
			foreach ($this->_defaultValues as $k => &$vDefault) {
				if ($vDefault instanceof AtomicInterface) {
					$v     = $this->{$k}->get();
					$vOrig = $v;

					yield $k => $v;

					if ($v !== $vOrig) {
						$this->{$k}->set($v);
					}
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
	 * This serialization, as opposed to JSON or BSON, does not unwrap the
	 * structured data. It only omits data that is part of the class definition.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		$toSerialize = [
			'_arrayOptions' => $this->_arrayOptions,
			'_jsonOptions'  => $this->_jsonOptions,
		];
		foreach ($this->_publicNames as $k) {
			$toSerialize[$k] = $this->{$k};
		}

		return serialize($toSerialize);
	}

	/**
	 * Constructs the object.
	 *
	 * This uses a faster but unsafe restore technique. It assumes that the
	 * serialized data was created by the local serialize method and was
	 * safely stored locally. No type checking is performed on restore. All
	 * data structure members have been serialized so no initialization of
	 * empty need be done.
	 *
	 * @link  https://php.net/manual/en/serializable.unserialize.php
	 *
	 * @param string $serialized The string representation of the object.
	 *
	 * @return void
	 */
	public function unserialize($serialized): void
	{
		$this->_initMetaData();

		$data = unserialize($serialized);

		foreach ($data as $k => $v) {
			$this->{$k} = $v;
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
	 * @param object|array|string|bool|null $in
	 *
	 * @return void
	 */
	public function replace($in): void
	{
		$this->_massageBlockInput($in);

		if (empty($in)) {
			return;
		}

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
	 * @param object|array|string|bool|null $in
	 *
	 * @return TypedClass
	 */
	public function merge($in)
	{
		$ret = clone $this;
		$ret->replace($in);

		return $ret;
	}

	/**
	 * @return array
	 */
	protected function _toArray(ArrayOptions $arrayOptions): array
	{
		$keepJsonExpr = $arrayOptions->has(ArrayOptions::KEEP_JSON_EXPR);
		$ZJE_STRING   = '\\Zend\\Json\\Expr';

		$arr = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);    //	AtomicInterface objects are returned as scalars.

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
					elseif ($this->{$k} instanceof DateTime) {
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
				if (empty($v)) {
					unset($arr[$k]);
				}
			}
		}

		return $arr;
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
	 * @return object|array
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
				break;

			case 'array':
				//	Test to see if it's an indexed or an associative array.
				//	Leave associative array as is.
				//	Copy indexed array by position to a named array
				if (!empty($in) && array_values($in) === $in) {
					$newArr   = [];
					$minCount = min(count($in), $this->_count);
					for ($i = 0; $i < $minCount; ++$i) {
						$newArr[$this->_publicNames[$i]] = $in[$i];
					}

					$in = $newArr;
				}
				break;

			case 'null':
			case 'NULL':
				$in = [];
				break;

			case 'bool':
			case 'boolean':
				/** A 'false' is returned by MySQL:PDO for "no results" */
				if (false === $in) {
					/** Change false to empty array. */
					$in = [];
					break;
				}
			//	A boolean 'true' falls through.

			default:
				throw new InvalidArgumentException('invalid input type: ' . gettype($in));
		}
	}

	/**
	 * Sets a variable to it's default value rather than unsetting it.
	 *
	 * @param string $k
	 */
	public function __unset($k)
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
	public function __get($k)
	{
		$this->_assertPropName($k);
		return $this->_getByName($k);
	}

	/**
	 * Set variable
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $k
	 * @param mixed  $v
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
	public function __isset($k): bool
	{
		return $this->_keyExists($k) && ($this->{$k} !== null);
	}

	/**
	 * Set data to named variable.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $propName
	 * @param mixed  $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName($propName, $in)
	{
		if (array_key_exists($propName, $this->_map)) {
			$propName = $this->_map[$propName];
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

		//	Treat DateTime related objects as atomic.
		if ($propertyDefaultValue instanceof DateTime) {
			$this->{$propName} = new $propertyClassType($in);
			return;
		}

		switch (gettype($in)) {
			case 'object':
				//	if identical types then reference the original object
				if ($propertyClassType === get_class($in)) {
					$this->{$propName} = $in;
				}

				//	Else give up.
				else {
					throw new InvalidArgumentException('cannot coerce object types');
				}
				break;

			case 'null':
			case 'NULL':
				$this->{$propName} = clone $propertyDefaultValue;
				break;

			case 'array':
				if ($propertyClassType === 'stdClass') {
					$this->{$propName} = (object)$in;
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
	protected function _assertPropName($k)
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
	protected function _getByName($propName)
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
	private function _keyExists($propName): bool
	{
		if (array_key_exists($propName, $this->_map)) {
			$propName = $this->_map[$propName];
		}

		return in_array($propName, $this->_publicNames);
	}
}
