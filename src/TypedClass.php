<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */


namespace Diskerror\Typed;

use DateTimeInterface;
use InvalidArgumentException;
use ReflectionObject;
use stdClass;
use Traversable;
use TypeError;
use function get_class;
use function gettype;
use function is_object;

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
	protected array $_map = [];

	/**
	 * Holds the name of the name of the child class for method_exists and property_exists.
	 *
	 * @var string
	 */
	private string $_calledClass;

	/**
	 * Holds the names of the called class' to-be-public properties in an indexed array.
	 *
	 * @var array
	 */
	private array $_publicNames = [];

	/**
	 * Holds the types of the called class to-be-public properties in associative array.
	 *
	 * @var array
	 */
	private array $_propertyTypes = [];

	/**
	 * Holds whether the called class of to-be-public properties allow null.
	 *
	 * @var array
	 */
	private array $_propertyAllowsNull = [];

	/**
	 * Holds the default values of the called class to-be-public properties in associative array.
	 *
	 * @var array
	 */
	private array $_defaultValues = [];

	/**
	 * Holds the count of the to-be-public properties.
	 *
	 * @var int
	 */
	private int $_count;


	/**
	 * Constructor.
	 * Accepts an object, array, or JSON string.
	 *
	 * @param mixed $param1 -OPTIONAL
	 * @param mixed $param2 -UNUSED
	 */
	public function __construct($param1 = null, $param2 = null)
	{
		parent::__construct();
		$this->_initializeObjects();
		$this->_initMetaData();
		$this->replace($param1);
	}

	/**
	 * Override to set default values for properties with object types.
	 *
	 * @return void
	 */
	protected function _initializeObjects()
	{
	}

	protected function _initMetaData()
	{
		$this->_calledClass = get_called_class();

		$ro = new ReflectionObject($this);

		//	Build array of default values with converted types.
		//	Ignore all properties starting with underscore, except "_id".
		foreach ($ro->getProperties() as $p) {
			$name       = $p->getName();
			$typeRefl   = $p->getType();
			$typeName   = !is_null($typeRefl) ? $typeRefl->getName() : '';
			$allowsNull = !is_null($typeRefl) ? $typeRefl->allowsNull() : true;

			if (($name[0] === '_' && $name !== '_id') || empty($name)) {
				continue;
			}

			$this->_publicNames[] = $name;
			$this->_propertyTypes[$name]      = $typeName;
			$this->_propertyAllowsNull[$name] = $allowsNull;

			if (isset($this->$name)) {
				if ($typeName === '') {
					if (is_object($this->$name)) {
						$this->_defaultValues[$name] = clone $this->$name;
					}
					else {
						$this->_defaultValues[$name] = $this->$name;
					}
				}
				elseif (self::_isAssignableType($typeName)) {
					$this->_defaultValues[$name] = $this->$name;
				}
				else {
					$this->_defaultValues[$name] = clone $this->$name;
				}
			}
			/* is not set */
			elseif ($allowsNull) {
				$this->_defaultValues[$name] = null;
				$this->$name                 = null;
			}
			elseif (self::_isAssignableType($typeName)) {
				$tmp = null;
				settype($tmp, $typeName);
				$this->_defaultValues[$name] = $tmp;
				$this->$name                 = $tmp;
			}
			else {
				$this->_defaultValues[$name] = new $typeName();
				$this->$name                 = new $typeName();
			}
		}

		$this->_count       = count($this->_publicNames);
	}

	/**
	 * Return array of sudo public property names.
	 *
	 * @return array
	 */
	protected function _getPublicNames()
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
		foreach ($this->_publicNames as $publicName) {
			$this->__unset($publicName);
		}

		$this->replace($in);
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
			foreach ($this->_publicNames as $k) {
				if (is_a($this->_propertyTypes[$k], AtomicInterface::class, true)) {
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
					if (!is_object($this->{$k}) || get_class($this->{$k}) !== $this->_propertyTypes[$k]) {
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
	public function __serialize(): ?array
	{
		$ret                  = $this->_toArray($this->_arrayOptions);
		$ret['_arrayOptions'] = $this->_arrayOptions->get();
		$ret['_jsonOptions']  = $this->_jsonOptions->get();

		return $ret;
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
		$this->_initializeObjects();

		$this->_arrayOptions = new ArrayOptions($data['_arrayOptions']);
		unset($data['_arrayOptions']);
		$this->_jsonOptions = new ArrayOptions($data['_jsonOptions']);
		unset($data['_jsonOptions']);

		$this->replace($data);
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
	 * @param array|stdClass $in
	 *
	 * @return void
	 */
	public function replace($in): void
	{
		if (is_scalar($in) && !is_string($in)) {
			throw new TypeError('Input must be an object, an array, or a JSON compatible string.');
		}

		$this->_massageInput($in);

		foreach ($in as $k => $v) {
			$k = $this->_getMappedName($k);

			if ($this->_keyExists($k)) {
				if (is_a($this->{$k}, TypedAbstract::class, true)) {
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
	public function merge($in): self
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
					if (is_a($v, TypedAbstract::class, true)) {
						$arr[$k] = $v->_toArray($arrayOptions);    //	??
					}
					elseif (is_a($v, DateTimeInterface::class, true)) {
						$arr[$k] = $v;    // maintain the type
					}
					elseif (method_exists($v, 'toArray')) {
						$arr[$k] = $v->toArray();
					}
					elseif (method_exists($v, '__toString')) {
						$arr[$k] = $v->__toString();
					}
					elseif ((is_a($v, '\\Laminas\\Json\\Expr', true)) && $keepJsonExpr) {
						$arr[$k] = $v;    // maintain the type
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

	/**
	 * Check if the input data is good or needs to be massaged.
	 *
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 * property (property names not starting with an underscore). Extra values
	 * are ignored. Unused properties are unchanged.
	 *
	 * @param mixed $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _massageInput(&$in): void
	{
		switch (gettype($in)) {
			case 'string':
				if ('' === $in) {
					$in = [];
				}
				else {
					$in        = json_decode($in);
					$lastError = json_last_error();
					if ($lastError !== JSON_ERROR_NONE) {
						throw new InvalidArgumentException(
							'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
							$lastError
						);
					}
				}
				break;

			case 'object':
				//	Leave object as is.
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
		$k = $this->_getMappedName($k);
		$this->_assertPropName($k);

//		if ($this->_propertyAllowsNull[$k]) {
//			$this->{$k} = null;
//			return;
//		}

		if (self::_isAssignableType($this->_propertyTypes[$k])) {
			$this->{$k} = $this->_defaultValues[$k];
			return;
		}

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
		$k = $this->_getMappedName($k);
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
		$k = $this->_getMappedName($k);
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
	 * Property name must exist.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $propName
	 * @param mixed $in
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName(string $propName, $in): void
	{
		$propertyDefaultValue = $this->_defaultValues[$propName];
		$propertyType         = (string) $this->_propertyTypes[$propName];
		$allowsNull           = (bool) $this->_propertyAllowsNull[$propName];

		if ($propertyType === '') {
			$this->{$propName} = $in;
			return;
		}

		if (self::_isAssignableType($propertyType)) {
			if ($in === null) {
				if ($allowsNull) {
					$this->{$propName} = null;
					return;
				}

				$this->{$propName} = $this->_defaultValues[$propName];
				return;
			}

			switch ($propertyType) {
				case 'bool':
					switch (gettype($in)) {
						case 'array':
						case 'object':
							$this->{$propName} = !empty((array) $in);
							return;
					}
					break;

				case 'string':
					if (is_array($in) || is_object($in)) {
						$this->{$propName} = json_encode($in);
						return;
					}
					break;
			}

			settype($in, $propertyType);
			$this->{$propName} = $in;
			return;
		}

		/** All properties are now handled as objects. */

		if (is_a($propertyType, TypedClass::class, true)) {
			$this->{$propName}->replace($in);
			return;
		}

		if (is_a($propertyType, TypedArray::class, true)) {
			$this->{$propName}->replace($in);
			return;
		}

		//	Handle our atomic types.
		if (is_a($propertyType, AtomicInterface::class, true)) {
			$this->{$propName}->set($in);
			return;
		}

		//	Handler for other types of objects.
		switch (gettype($in)) {
			case 'object':
				//	if identical types then reference the original object
				if ($propertyType === get_class($in)) {
					$this->{$propName} = $in;
				}
				else {
					//	First try to absorb the input in its entirety,
					try {
						$this->{$propName} = new $propertyType($in);
					}
						//	Then try to copy matching members by name.
					catch (TypeError $t) {
						$this->{$propName} = new $propertyType((array) $in);
					}
				}
				break;

			case 'null':
			case 'NULL':
				$this->{$propName} = clone $propertyDefaultValue;
				break;

			case 'array':
				if ($propertyType === 'stdClass') {
					$this->{$propName} = (object) $in;
					break;
				}
			//	fall through

			default:
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				$this->{$propName} = new $propertyType($in);
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

	protected function _getMappedName(string $name): string
	{
		return array_key_exists($name, $this->_map) ? $this->_map[$name] : $name;
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
	 * Get variable by name. Name must exist.
	 *
	 * @param string $propName
	 *
	 * @return mixed
	 */
	protected function _getByName($propName)
	{
		if (is_a($this->{$propName}, AtomicInterface::class, true)) {
			return $this->{$propName}->get();
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
		return in_array($this->_getMappedName($propName), $this->_publicNames);
	}
}
