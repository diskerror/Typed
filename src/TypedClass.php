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
 * This requires a version PHP 8.1 or greater.
 *
 * Create a child of this class with your named properties as public, or
 *      they can be protected or private and be a decendent of
 *            * TypedAbstract,
 *        * AtomicInterface,
 *            * Diskerror\Typed\DateTime, or
 *            * named "_id",
 *        and these will be treated as public members but with additional sanatation.
 *
 * Property names CANNOT begin with an underscore (except "_id"). This maintains the Zend Framework
 *      convention that protected and private property names should begin with an
 *      underscore.
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

	private int   $_count;
	private array $_publicNames;


	/**
	 * Constructor.
	 * Accepts an object, array, or JSON string.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = [])
	{
		$this->_initToArrayOptions();

		//	Build metadata array.
		$this->_meta = [];
		$ro          = new ReflectionObject($this);

		//	Build array of initValue values with converted types.
		foreach ($ro->getProperties() as $p) {
			$pName    = $p->getName();
			$typeObj  = $p->getType();
			$typeName = !is_null($typeObj) ? $typeObj->__toString() : '';
			$typeName = substr($typeName, 0, 1) === '?' ? substr($typeName, 1) : $typeName;
			$isObject = !self::_isAssignable($typeName);

			//	If the name is not in the list of options...
			//	If the name begins with an underscore and is not "_id"...
			//	If the name is empty...
			//	If the property is public...
			if (
				!in_array($pName, $this->_optionList)
				&& ($pName[0] !== '_' || $pName === '_id')
				&& !empty($pName)
				&& ($p->isPublic() || $isObject)
			) {
				$isNullable = is_null($typeObj) || $typeObj->allowsNull();

				if (!$isNullable && $isObject && !isset($this->$pName)) {
					$this->$pName = new $typeName();
				}

				$this->_meta[$pName] = new PropertyMetaData($typeName, $isObject, $isNullable, $p->isPublic());
			}
		}

		if ($in !== []) {
			$this->assign($in);
		}
	}

	/**
	 * Return array of pseudo public property names.
	 *
	 * @return array
	 */
	final public function getPublicNames(): array
	{
		if (!isset($this->_publicNames)) {
			$this->_publicNames = array_keys($this->_meta);
		}
		return $this->_publicNames;
	}

	/**
	 * Required method for Countable.
	 *
	 * @return int
	 */
	final public function count(): int
	{
		if (!isset($this->_count)) {
			$this->_count = count($this->_meta);
		}
		return $this->_count;
	}

	/**
	 * Assign local values with matches from input.
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
	public function assign($in): void
	{
		$this->_massageInput($in);
		$this->_massageInputArray($in);

		foreach ($in as $k => $v) {
			$k = array_key_exists($k, $this->_map) ? $this->_map[$k] : $k;
			if ($this->_keyExists($k)) {
				$this->_setByName($k, $v);
			}
		}

		$this->_checkRelatedProperties();
	}

	/**
	 * Clear all values.
	 *
	 * All values are set to zero or empty.
	 *
	 * @return void
	 */
	public function clear(): void
	{
		foreach ($this->_meta as $pName => $meta) {
			$pType = $meta->type;
			switch (true) {
				case is_a($pType, AtomicInterface::class, true):
					$this->$pName->set(null);
					break;

				case is_a($pType, TypedAbstract::class, true):
					$this->$pName->clear();
					break;

				case is_object($this->$pName):
					$this->$pName = new $pType();
					break;

				default:
					$this->$pName = ScalarAbstract::setType('', gettype($this->$pName));
					break;
			}
		}
	}

	/**
	 * Clone local values and assign matching values with input.
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
		$clone->assign($in);

		return $clone;
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
					case is_a($v, AtomicInterface::class, true):
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
	protected function _massageInputArray(&$in): void
	{
		//	If input is an array, test to see if it's an indexed or an associative array.
		//	Leave associative array as is.
		//	Copy indexed array by position to a named array
		if (is_array($in) && array_is_list($in)) {
			$newArr   = [];
			$minCount = min(count($in), $this->count());
			for ($i = 0; $i < $minCount; ++$i) {
				$newArr[$this->getPublicNames()[$i]] = $in[$i];
			}

			$in = $newArr;
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
		//	Allow handling of array option object.
		if (in_array($pName, $this->_optionList)) {
			return $this->$pName;
		}

		$pName = array_key_exists($pName, $this->_map) ? $this->_map[$pName] : $pName;
		$this->_assertPropName($pName);
		return ($this->$pName instanceof AtomicInterface) ? $this->$pName->get() : $this->$pName;
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
		if ($this->_meta[$pName]->isPublic) {
			$this->$pName = $in;
			return;
		}

		$pType = $this->_meta[$pName]->type;

		switch (true) {
			case $in === null;
				switch (true) {
					case is_a($pType, AtomicInterface::class, true):
						$this->$pName->set(null);
						break;

					case is_a($pType, TypedAbstract::class, true):
						$this->$pName->clear();
						break;

					default:
						$this->$pName = new $pType();
				}
				return;


			case is_a($pType, TypedAbstract::class, true):
				if ($deepCopy) {
					$this->$pName->assign($in);
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
						foreach ($in as $k => $v) {
							$this->$pName->{$k} = $v;
						}
					}
				}
				return;

			case is_array($in):
				if ($pType === 'stdClass') {
					$this->$pName = (object)$in;
					break;
				}
				$this->$pName = new $pType($in);
				return;

			case $pType === 'bool':
			case $pType === 'int':
			case $pType === 'double':
			case $pType === 'string':
				if (!$this->_meta[$pName]->isNullable && $in === null) {
					$in = '';
				}
				$this->$pName = ScalarAbstract::setType($in, $pType);
				return;

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
