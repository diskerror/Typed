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
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = null)
	{
		$this->_initToArrayOptions();
		$this->_initializeObjects();
		$this->_initMetaData();
		if ($in !== null) {
			$this->replace($in);
		}
	}

	/**
	 * Override to set default values for properties with object types.
	 *
	 * @return void
	 */
	protected function _initializeObjects()
	{
	}

	final protected function _initMetaData()
	{
		$this->_calledClass = get_called_class();

		$ro = new ReflectionObject($this);

		//	Build array of default values with converted types.
		//	Ignore all properties starting with underscore, except "_id".
		foreach ($ro->getProperties() as $p) {
			$name = $p->getName();

			if (
				in_array($name, ['toArrayOptions', 'serializeOptions', 'toJsonOptions'])
				|| ($name[0] === '_' && $name !== '_id')
				|| empty($name)
			) {
				continue;
			}

			$typeRefl   = $p->getType();
			$typeName   = !is_null($typeRefl) ? $typeRefl->getName() : '';
			$allowsNull = is_null($typeRefl) || $typeRefl->allowsNull();

			$this->_publicNames[]             = $name;
			$this->_propertyTypes[$name]      = $typeName;
			$this->_propertyAllowsNull[$name] = $allowsNull;

			$tmp = null;

			if (isset($this->$name) && $this->$name !== null) {
				if ($typeName === '') {
					if (is_object($this->$name)) {
						$this->_defaultValues[$name] = clone $this->$name;
					}
					else {
						$this->_defaultValues[$name] = $this->$name;
					}
				}
				elseif (self::_isNonObject($typeName)) {
					$this->_defaultValues[$name] = $this->$name;
				}
				elseif (is_a($typeName, TypedAbstract::class, true)) {
					$this->_defaultValues[$name] = $this->$name->toArray();
				}
				elseif (is_a($typeName, ScalarAbstract::class, true)) {
					$this->_defaultValues[$name] = $this->$name->get();
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
			elseif (self::_setBasicTypeAndConfirm($tmp, $typeName)) {
				$this->_defaultValues[$name] = $tmp;
				$this->$name                 = $tmp;
			}
			else {
				$this->_defaultValues[$name] = null;
				$this->$name                 = new $typeName();
			}
		}

		$this->_count = count($this->_publicNames);
	}

	private function _massageInputArray(&$in)
	{
		//	If input is an array, test to see if it's an indexed or an associative array.
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
		//	else leave as is
	}

	/**
	 * Return array of sudo public property names.
	 *
	 * @return array
	 */
	final protected function _getPublicNames()
	{
		return $this->_publicNames;
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

					if ($v != $vOrig) {
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

		$propertiesSet = [];
		foreach ($in as $k => $v) {
			$k = $this->_getMappedName($k);

			if ($this->_keyExists($k)) {
				$this->_setByName($k, $v, false);
				$propertiesSet[] = $k;
			}
		}

		$propertiesRemaining = array_diff($this->_publicNames, $propertiesSet);
		foreach ($propertiesRemaining as $pName) {
			$this->_setByName($pName, null, false);
		}

		$this->_checkRelatedProperties();
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
			$k = $this->_getMappedName($k);

			if ($this->_keyExists($k)) {
				$this->_setByName($k, $v);
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
		$omitEmpty       = $arrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$omitDefaults    = $arrayOptions->has(ArrayOptions::OMIT_DEFAULTS);
		$omitResources   = $arrayOptions->has(ArrayOptions::OMIT_RESOURCES);
		$dateToString    = $arrayOptions->has(ArrayOptions::DATE_OBJECT_TO_STRING);
		$objectsToString = $arrayOptions->has(ArrayOptions::ALL_OBJECTS_TO_STRING);
		$keepJsonExpr    = $arrayOptions->has(ArrayOptions::KEEP_JSON_EXPR);

		$arrayRes = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);    //  AtomicInterface objects are returned as scalars.

			if ($omitEmpty && (empty($v) || (is_object($v) && empty((array) $v)))) {
				continue;
			}

			if ($omitDefaults) {
				$default = $this->_defaultValues[$k];

				if (is_a($default, AtomicInterface::class, true)) {
					$default = $default->get();
				}

				if ($v == $default) {
					continue;
				}
			}

			switch (gettype($v)) {
				case 'resource':
					if ($omitResources) {
						continue 2;
					}
					break;

				case 'object':
					switch (true) {
						case is_a($v, TypedAbstract::class, true):
							$arrayRes[$k] = $v->_toArray($arrayOptions);
							break;

						case is_a($v, DateTimeInterface::class, true):
							if ($dateToString) {
								//	remove trailing zeros, and trim spaces just in case
								$arrayRes[$k] = trim($v->format(DateTime::MYSQL_STRING_IO_FORMAT_MICRO), '0 ');
							}
							else {
								$arrayRes[$k] = $v;
							}
							break;

						case $keepJsonExpr && is_a($v, '\\Laminas\\Json\\Expr', true):
							$arrayRes[$k] = $v;    // return as \Laminas\Json\Expr
							break;

						case method_exists($v, 'toArray'):
							$arrayRes[$k] = $v->toArray();
							break;

						case $objectsToString && method_exists($v, '__toString'):
							$arrayRes[$k] = $v->__toString();
							break;

						case true:
							$arrayRes[$k] = $v;
							break;
					}
					break;

				//	nulls, bools, ints, floats, strings, and arrays
				default:
					$arrayRes[$k] = $v;
			}
		}

		return $arrayRes;
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
	 * @param string $pName
	 *
	 * @return mixed
	 */
	public function __get(string $pName)
	{
		$pName = $this->_getMappedName($pName);
		$this->_assertPropName($pName);
		return $this->_getByName($pName);
	}

	/**
	 * Set variable
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $pName
	 * @param mixed $val
	 */
	public function __set(string $pName, $val)
	{
		$pName = $this->_getMappedName($pName);
		$this->_assertPropName($pName);
		$this->_setByName($pName, $val);
		$this->_checkRelatedProperties();
	}

	/**
	 * Is a variable set?
	 *
	 * Behavior for "isset()" expects the variable (property) to exist and not be null.
	 *
	 * @param string $pName
	 *
	 * @return bool
	 */
	public function __isset(string $pName): bool
	{
		return $this->_keyExists($pName) && ($this->{$pName} !== null);
	}

	/**
	 * Sets a variable to its default value rather than unsetting it.
	 *
	 * @param string $pName
	 */
	public function __unset(string $pName)
	{
		$pName = $this->_getMappedName($pName);
		$this->_assertPropName($pName);
		$this->_setByName($pName, null);
		$this->_checkRelatedProperties();
	}

	/**
	 * Set data to named variable.
	 * Property name must exist.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $pName
	 * @param mixed $in
	 * @param bool $deepCopy
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName(string $pName, $in, bool $deepCopy = true): void
	{
		$pType = $this->_propertyTypes[$pName];

		switch (true) {
			case $in === null;
				if ($this->_propertyAllowsNull[$pName]) {
					$this->$pName = null;
				}
				elseif (self::_isNonObject($pType)) {
					$this->$pName = $this->_defaultValues[$pName];
				}
				elseif (is_a($pType, TypedAbstract::class, true)) {
					$this->$pName->assign($this->_defaultValues[$pName]);
				}
				elseif (is_a($pType, ScalarAbstract::class, true)) {
					$this->$pName->set($this->_defaultValues[$pName]);
				}
				else {
					$this->$pName = clone $this->_defaultValues[$pName];
				}
				return;

			case self::_setBasicTypeAndConfirm($in, $pType):
				$this->$pName = $in;
				return;

			case is_a($pType, AtomicInterface::class, true):
				$this->_setPropertyIfNotSet($pName);
				$this->$pName->set($in);
				return;

			case is_a($pType, TypedAbstract::class, true):
				if ($deepCopy) {
					$this->_setPropertyIfNotSet($pName);
					$this->$pName->replace($in);
					return;
				}
				else {
					if (is_object($in) && get_class($in) === $pType) {
						$this->$pName = clone $in;
					}
					else {
						try {
							$this->$pName = new $pType($in);
						}
							//	Then try to copy members by name.
						catch (TypeError $t) {
							$this->_setPropertyIfNotSet($pName);
							foreach ($in as $k => $v) {
								$this->$pName->{$k} = $v;
							}
						}
					}
				}
				return;

			case is_object($in):
				//	if identical types then reference the original object
				if ($pType === get_class($in)) {
					$this->$pName = $in;
				}
				else {
					//	First try to absorb the input in its entirety,
					try {
						$this->$pName = new $pType($in);
					}
						//	Then try to copy members by name.
					catch (TypeError $t) {
						$this->_setPropertyIfNotSet($pName);
						foreach ($in as $k => $v) {
							$this->$pName->{$k} = $v;
						}
					}
				}
				return;

			case is_array($in):
				if ($pType === 'stdClass') {
					$this->$pName = (object) $in;
					break;
				}
			//	fall through

			default:
				//	NULL is handled above.
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				$this->$pName = new $pType($in);
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

	protected function _getMappedName(string $pName): string
	{
		return array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
	}

	private function _setPropertyIfNotSet(string $pName): void
	{
		$type = $this->_propertyTypes[$pName];
		if (!isset($this->$pName) && !self::_isNonObject($type)) {
			$this->$pName = new $type();
		}
	}

	/**
	 * Throws exception if named property does not exist.
	 *
	 * @param string $pName
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _assertPropName($pName)
	{
		if (!$this->_keyExists($pName)) {
			throw new InvalidArgumentException();
		}
	}

	/**
	 * Get variable by name. Name must exist.
	 *
	 * @param string $pName
	 *
	 * @return mixed
	 */
	protected function _getByName($pName)
	{
		if (is_a($this->{$pName}, AtomicInterface::class)) {
			return $this->{$pName}->get();
		}

		return $this->{$pName};
	}

	/**
	 * Returns true if key/prop name exists or is mappable.
	 *
	 * @param string $pName
	 *
	 * @return bool
	 */
	private function _keyExists($pName): bool
	{
		return in_array($this->_getMappedName($pName), $this->_publicNames);
	}


	/**
	 * String representation of PHP object.
	 *
	 * This serialization, as opposed to JSON or BSON, does not unwrap the
	 * structured data. It only omits data that is part of the class definition.
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return ?array the string representation of the object or null
	 */
	public function __serialize(): ?array
	{
		return array_merge(parent::__serialize(), $this->_toArray($this->serializeOptions));
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
		parent::__unserialize($data);

		$this->_initMetaData();

		$this->replace($data);
	}
}
