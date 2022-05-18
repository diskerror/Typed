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
 *      protected or private, and initValue values of the desired type. Property
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
 * The ideal usage of this abstract class is as the parent class of a data set
 *      where the input to the constructor (or assign) method is an HTTP request
 *      object. It will help with filtering and insuring the existence of initValue
 *      values for missing input parameters.
 */
abstract class TypedClass extends TypedAbstract
{
	use SetTypeTrait;

	/**
	 * Holds the name pairs for when different/bad key names need to point to the same data.
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Holds the default values of the called class to-be-public properties in associative array.
	 *
	 * @var array
	 */
	private $_defaultValues;


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

		$this->_initToArrayOptions();
		$this->_initMetaData();
		$this->_initProperties();
		if ($in !== null) {
			$this->replace($in);
		}
	}

	/**
	 * Initialize meta data.
	 *
	 * @return void
	 */
	final protected function _initMetaData()
	{
		$calledClass = get_called_class();

		//	Build array of default values with converted types.
		//	First, get all class properties then remove elements with names starting with underscore, except "_id".
		$prClass = get_parent_class($calledClass);
		while (substr($prClass, -10) !== 'TypedClass') {
			//	We're looking for Typed\TypedClass or TypedBSON\TypedClass.
			$prClass = get_parent_class($prClass);
		}

		$this->_defaultValues =
			array_diff_key(get_class_vars($calledClass), get_class_vars($prClass));

		foreach ($this->_defaultValues as $k => &$v) {
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
				$this->$k = clone $v;
			}
		}
	}

	/**
	 * Return array of sudo public property names.
	 *
	 * @return array
	 */
	final public function getPublicNames(): array
	{
		static $pNames;
		if (!isset($pNames)) {
			$pNames = array_keys($this->_defaultValues);
		}
		return $pNames;
	}

	/**
	 * Return array of sudo public property names.
	 *
	 * @return array
	 */
	protected final function _getDefaults()
	{
		return $this->_defaultValues;
	}

	/**
	 * Required method for Countable.
	 *
	 * @return int
	 */
	final public function count(): int
	{
		static $count;
		if (!isset($count)) {
			$count = count($this->_defaultValues);
		}
		return $count;
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
	 * @param array|object $in
	 *
	 * @return void
	 */
	public function assign($in): void
	{
		$this->_massageInput($in);

		$propertiesSet = [];
		foreach ($in as $k => $v) {
			$k = array_key_exists($k, $this->_map) ? $this->_map[$k] : $k;

			if ($this->_keyExists($k)) {
				$this->_setByName($k, $v);
				$propertiesSet[] = $k;
			}
		}

		$this->restoreInitialValues($propertiesSet);

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
	 * @param array|object $in
	 *
	 * @return void
	 */
	public function replace($in): void
	{
		$this->_massageInput($in);

		foreach ($in as $k => $v) {
			$k = array_key_exists($k, $this->_map) ? $this->_map[$k] : $k;
			if ($this->_keyExists($k) and null !== $v) {
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
	 * @param array|object $in
	 *
	 * @return TypedAbstract
	 */
	public function merge($in): TypedAbstract
	{
		$clone = clone $this;
		$clone->replace($in);

		return $clone;
	}

	/**
	 * @param array $namesToOmit -OPTIONAL
	 *
	 * @return void
	 */
	public function restoreInitialValues(array $namesToOmit = [])
	{
		$propertiesRemaining = array_diff($this->getPublicNames(), $namesToOmit);
		foreach ($propertiesRemaining as $pName) {
			$this->$pName = is_object($this->_defaultValues[$pName]) ?
				clone $this->_defaultValues[$pName] :
				$this->_defaultValues[$pName];
		}
	}

	/**
	 * @return void
	 */
	public function setArrayOptionsToNested(): void
	{
		foreach ($this->getPublicNames() as $k) {
			if ($this->$k instanceof TypedAbstract) {
				$this->$k->toArrayOptions->set($this->toArrayOptions->get());
				$this->$k->setArrayOptionsToNested();
			}
		}
	}

	/**
	 * @return void
	 */
	public function setJsonOptionsToNested(): void
	{
		foreach ($this->getPublicNames() as $k) {
			if ($this->$k instanceof TypedAbstract) {
				$this->$k->toJsonOptions->set($this->toJsonOptions->get());
				$this->$k->setJsonOptionsToNested();
			}
		}
	}

	/**
	 * Returns an array representation of the data contents of the object.
	 *
	 * @return array
	 */
	public function toArray(): array
	{
		$omitEmpty       = $this->toArrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$omitDefaults    = $this->toArrayOptions->has(ArrayOptions::OMIT_DEFAULTS);
		$omitResources   = $this->toArrayOptions->has(ArrayOptions::OMIT_RESOURCE);
		$dateToString    = $this->toArrayOptions->has(ArrayOptions::DATE_OBJECT_TO_STRING);
		$objectsToString = $this->toArrayOptions->has(ArrayOptions::ALL_OBJECTS_TO_STRING);

		$arr = [];
		foreach ($this->getPublicNames() as $k) {
			$v = $this->$k;

			if ($omitDefaults && $v == $this->_defaultValues[$k]) {
				continue;
			}

			switch (gettype($v)) {
				case 'object':
					switch (true) {
						case $v instanceof AtomicInterface:
							$v = $v->get();
							break;

						case method_exists($v, 'toArray'):
							$v = $v->toArray();
							break;

						case $dateToString && $v instanceof DateTime:
							// This is without timezone for MySQL.
						case $objectsToString && method_exists($v, '__toString'):
							$v = $v->__toString();
							break;
					}
					break;

				case 'resource':
					if ($omitResources) {
						continue 2;
					}
					break;

				//	nulls, bools, ints, floats, strings, and arrays
				default:
					// Just copy it.
			}

			//	Testing for empty must happen after nested objects have been reduced.
			if ($omitEmpty && self::_isEmpty($v)) {
				continue;
			}

			$arr[$k] = $v;
		}

		return $arr;
	}

	/**
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		$omitEmpty       = $this->toJsonOptions->has(JsonOptions::OMIT_EMPTY);
		$omitDefaults    = $this->toJsonOptions->has(JsonOptions::OMIT_DEFAULTS);
		$objectsToString = $this->toJsonOptions->has(JsonOptions::ALL_OBJECTS_TO_STRING);
		$keepJsonExpr    = $this->toJsonOptions->has(JsonOptions::KEEP_JSON_EXPR);
		$ZJE             = '\\Laminas\\Json\\Expr';

		$arr = [];
		foreach ($this->getPublicNames() as $k) {
			$v = $this->$k;

			if ($omitDefaults && $v == $this->_defaultValues[$k]) {
				continue;
			}

			switch (gettype($v)) {
				case 'object':
					switch (true) {
						case $v instanceof AtomicInterface:
							$v = $v->get();
							break;

						case method_exists($v, 'jsonSerialize'):
							$v = $v->jsonSerialize();
							break;

						case method_exists($v, 'toArray'):
							$v = $v->toArray();
							break;

						case $keepJsonExpr && $v instanceof $ZJE:
							// return as \Laminas\Json\Expr
							break;

						case $objectsToString && method_exists($v, '__toString'):
							$v = $v->__toString();
							break;

						default:
					}
					break;

				case 'resource':
					continue 2;

				//	nulls, bools, ints, floats, strings, and arrays
				default:
					// Just copy it.
			}

			if ($omitEmpty && self::_isEmpty($v)) {
				continue;
			}

			$arr[$k] = $v;
		}

		return $arr;
	}

	/**
	 * All member objects will be deep cloned.
	 */
	public function __clone()
	{
		foreach ($this as $k => $v) {
			if (is_object($v)) {
				$this->$k = clone $v;
			}
		}
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
					$v = $this->$k->get();
					yield $k => $v;
					$this->$k->set($v);
				}
				else {
					yield $k => $this->$k;

					//	Cast if not the same type.
					if (!is_object($this->$k) || get_class($this->$k) !== get_class($vDefault)) {
						$this->_setByName($k, $this->$k);
					}
					//	Null property types don't get checked.
				}
			}
		})();
	}

	/**
	 * @param $in
	 *
	 * @return void
	 */
	protected function _massageInput(&$in): void
	{
		parent::_massageInput($in);

		//	Test to see if it's an indexed or an associative array.
		//	Leave associative array as is.
		//	Copy indexed array by position to a named array
		if (is_array($in) && !empty($in) && array_values($in) === $in) {
			$newArr   = [];
			$minCount = min(count($in), $this->count());
			$pNames   = $this->getPublicNames();
			for ($i = 0; $i < $minCount; ++$i) {
				$newArr[$pNames[$i]] = $in[$i];
			}

			$in = $newArr;
		}
		//	else leave as is
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
		//	Allow handling of array option object.
		if (in_array($pName, $this->_optionList)) {
			return $this->$pName;
		}

		$pName = array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
		$this->_assertPropName($pName);
		return $this->$pName instanceof AtomicInterface ? $this->$pName->get() : $this->$pName;
	}

	/**
	 * Set variable
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $pName
	 * @param mixed  $val
	 */
	public function __set(string $pName, $val)
	{
		$pName = array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
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
		$pName = array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
		return $this->_keyExists($pName) && ($this->$pName !== null);
	}

	/**
	 * Sets a variable to its initValue value rather than unsetting it.
	 *
	 * @param string $pName
	 */
	public function __unset(string $pName)
	{
		$pName = array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
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
	 * @param mixed  $in
	 * @param bool   $deepCopy -OPTIONAL
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName(string $pName, $in): void
	{
		if (!in_array($pName, $this->getPublicNames())) {
			return;
		}

		/** All properties are now handled as objects. */
		$propDefaultValue = $this->_defaultValues[$pName];

		//	Handle our atomic types.
		if ($propDefaultValue instanceof AtomicInterface) {
			$this->$pName->set($in);
			return;
		}

		//	Handle our two special object types.
		if ($propDefaultValue instanceof TypedClass) {
			$this->$pName->replace($in);
			return;
		}

		if ($propDefaultValue instanceof TypedArray) {
			$this->$pName->assign($in);
			return;
		}

		//	Handler for other types of objects.
		$pType = get_class($propDefaultValue);
		switch (gettype($in)) {
			case 'object':
				//	if identical types then reference the original object
				if ($pType === get_class($in)) {
					$this->$pName = $in;
				}
				else {
					//	First try to absorb the input in its entirety,
					try {
						$this->$pName = new $pType($in);
					}
						//	Then try to copy matching members by name.
					catch (TypeError $t) {
						$this->replace($in);
					}
				}
				break;

			case 'null':
			case 'NULL':
				$this->$pName = clone $propDefaultValue;
				break;

			case 'array':
				if ($pType === 'stdClass') {
					$this->$pName = (object) $in;
					break;
				}
			//	fall through

			default:
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

	/**
	 * Throws exception if named property does not exist.
	 *
	 * @param string $pName
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _assertPropName(string $pName)
	{
		if (!$this->_keyExists($pName)) {
			throw new InvalidArgumentException($pName);
		}
	}

	/**
	 * Returns true if key/prop name exists or is mappable.
	 *
	 * @param string $pName
	 *
	 * @return bool
	 */
	private function _keyExists(string $pName): bool
	{
		return in_array($pName, $this->getPublicNames());
	}
}
