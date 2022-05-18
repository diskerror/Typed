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
use InvalidArgumentException;
use ReflectionObject;
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
	protected array $_map = [];

	/**
	 * Holds information about each property.
	 *
	 * @var array
	 */
	protected array $_meta;


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
	 * Override to set initValue values for properties with object types.
	 *
	 * @return void
	 */
	protected function _initializeObjects()
	{
	}

	/**
	 * Initialize meta data.
	 *
	 * @return void
	 */
	final protected function _initMetaData()
	{
		$this->_meta = [];
		$ro          = new ReflectionObject($this);

		//	Build array of initValue values with converted types.
		//	Ignore all properties starting with underscore, except "_id".
		foreach ($ro->getProperties() as $p) {
			$pName = $p->getName();

			if (
				in_array($pName, $this->_optionList)
				|| ($pName[0] === '_' && $pName !== '_id')
				|| empty($pName)
			) {
				continue;
			}

			$typeObj    = $p->getType();
			$typeName   = !is_null($typeObj) ? $typeObj->getName() : '';
			$isNullable = is_null($typeObj) || $typeObj->allowsNull();
			$isObject   = !self::_isAssignable($typeName);

			if (!isset($this->$pName)) {
				switch (true) {
					case $isObject:
						//	All objects will be initialized.
						if (is_a($typeName, AtomicInterface::class, true)) {
							$this->$pName = new $typeName('', $isNullable);
							$isNullable   = false;
						}
						else {
							$this->$pName = new $typeName();
						}
						break;

					case $isNullable:
						$this->$pName = null;
						break;

					default:
						$this->$pName = self::setType('', $typeName);
				}
			}

			$initialValue = $isObject ? clone $this->$pName : $this->$pName;

			$this->_meta[$pName] = new PropertyMetaData($typeName, $isObject, $isNullable, $initialValue);
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
			$pNames = array_keys($this->_meta);
		}
		return $pNames;
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
			$count = count($this->_meta);
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
		$this->_massageInputArray($in);

		$propertiesSet = [];
		foreach ($in as $k => $v) {
			$k = array_key_exists($k, $this->_map) ? $this->_map[$k] : $k;

			if ($this->_keyExists($k)) {
				$this->_setByName($k, $v, false);
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
		$this->_massageInputArray($in);

		foreach ($in as $k => $v) {
			$k = array_key_exists($k, $this->_map) ? $this->_map[$k] : $k;
			if ($this->_keyExists($k) and ($this->_meta[$k]->isNullable or null !== $v)) {
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
			$this->$pName = $this->_meta[$pName]->isObject ?
				clone $this->_meta[$pName]->initValue :
				$this->_meta[$pName]->initValue;
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
		$omitResources   = $this->toArrayOptions->has(ArrayOptions::OMIT_RESOURCE);
		$dateToString    = $this->toArrayOptions->has(ArrayOptions::DATE_OBJECT_TO_STRING);
		$objectsToString = $this->toArrayOptions->has(ArrayOptions::ALL_OBJECTS_TO_STRING);

		$arr = [];
		foreach ($this->_meta as $k => $meta) {
			$v = $this->$k;

			if ($meta->isObject) {
				switch (true) {
					case $v instanceof AtomicInterface:
						$v = $v->get();
						break;

					case method_exists($v, 'toArray'):
						$v = $v->toArray();
						break;

					case is_a($v, DateTimeInterface::class, true):
						if ($dateToString) {
							$v = $v->__toString();
						}
						break;

					case $objectsToString && method_exists($v, '__toString'):
						$v = $v->__toString();
						break;
				}
			}
			elseif (is_resource($v) && $omitResources) {
				continue;
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
		$objectsToString = $this->toJsonOptions->has(JsonOptions::ALL_OBJECTS_TO_STRING);
		$keepJsonExpr    = $this->toJsonOptions->has(JsonOptions::KEEP_JSON_EXPR);
		$ZJE             = '\\Laminas\\Json\\Expr';

		$arr = [];
		foreach ($this->getPublicNames() as $k) {
			$v = $this->$k;

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
			foreach ($this->getPublicNames() as $k) {
				if (is_a($this->_meta[$k]->type, AtomicInterface::class, true)) {
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
					if (!is_object($this->{$k}) || get_class($this->{$k}) !== $this->_meta[$k]->type) {
						$this->_setByName($k, $this->{$k});
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
	protected function _massageInputArray(&$in)
	{
		//	If input is an array, test to see if it's an indexed or an associative array.
		//	Leave associative array as is.
		//	Copy indexed array by position to a named array
		if (is_array($in) && !empty($in) && array_values($in) === $in) {
			$newArr   = [];
			$minCount = min(count($in), $this->count());
			for ($i = 0; $i < $minCount; ++$i) {
				$newArr[$this->getPublicNames()[$i]] = $in[$i];
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
	protected function _setByName(string $pName, $in, bool $deepCopy = true): void
	{
		$pType = $this->_meta[$pName]->type;

		switch (true) {
			case $in === null;
				switch (true) {
					case is_a($pType, AtomicInterface::class, true):
						$this->$pName->set(null);
						break;

					case is_a($pType, TypedAbstract::class, true):
						$this->$pName->assign([]);
						break;

					case $this->_meta[$pName]->isNullable:
						$this->$pName = null;
						break;

					case self::_isAssignable($pType):
						$this->$pName = self::setType($in, $pType);
						break;

					default:
						$this->$pName = new $pType();
				}
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

			case self::_setBasicTypeAndConfirm($in, $pType):
				$this->$pName = $in;
				return;

			case is_a($pType, AtomicInterface::class, true):
				$this->_setPropertyIfNotSet($pName);
				$this->$pName->set($in);
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

	/**
	 * @param string $pName
	 *
	 * @return void
	 */
	private function _setPropertyIfNotSet(string $pName): void
	{
		$type = $this->_meta[$pName]->type;
		if (!isset($this->$pName) && !self::_isAssignable($type)) {
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
