<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name        TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use DateTimeInterface;
use MongoDB\BSON\{UTCDateTime, UTCDateTimeInterface, Persistable};
use Traversable;
use InvalidArgumentException;

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
abstract class TypedClass implements TypedInterface, Persistable
{
	/**
	 * Holds the name pairs for when different/bad key names need to point to the same data.
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Holds options for "toArray" customizations.
	 *
	 * @var \Diskerror\Typed\ArrayOptions
	 */
	protected $_arrayOptions;

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
	private $_defaultVars;

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
		$this->_init();

		switch (gettype($in)) {
			case 'string':
			case 'array':
			case 'object':
				$this->assign($in);
				break;

			//	Don't waste time with assign if input is one of these.
			//		Just return leaving the default values.
			case 'NULL':
			case 'null':
			case 'bool':
			case 'boolean':
				if (!$in) {
					return;
				}
			//	bool TRUE falls through

			default:
				throw new InvalidArgumentException('bad value to constructor');
		}

	}

	/**
	 * Copies all matching property names while maintaining original types and
	 *     doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $input,
	 *     leaves unmatched properties in this class untouched, and
	 *     skips names starting with an underscore.
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 *    property (property names not starting with an underscore). Extra values
	 *    are ignored. Unused properties are unchanged.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param object|array|string|bool|null $in -OPTIONAL
	 */
	public function assign($in = null)
	{
		//	First check if the input data is good or needs to be massaged.
		switch (gettype($in)) {
			case 'object':
				break;

			case 'array':
				//	Test to see if it's an indexed or an associative array.
				//	Leave associative array as is.
				//	Copy indexed array by position to a named array
				if (array_values($in) === $in) {
					$newArr   = [];
					$minCount = min(count($in), $this->_count);
					for ($i = 0; $i < $minCount; ++$i) {
						$newArr[$this->_publicNames[$i]] = $in[$i];
					}

					$in = &$newArr;
				}
				break;

			case 'string':
				$in          = json_decode($in);
				$jsonLastErr = json_last_error();
				if ($jsonLastErr !== JSON_ERROR_NONE) {
					throw new \UnexpectedValueException(
						'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
						$jsonLastErr
					);
				}
				break;

			case 'null':
			case 'NULL':
			case 'bool':
			case 'boolean': //	a 'false' is returned by MySQL:PDO for "no results"
				//	So, return default values;
				if ($in !== true) {    //	do only if false or null. True does nothing.
					foreach ($this->_defaultVars as $k => &$v) {
						$this->__unset($k);
					}

					return;
				}
			//	A boolean 'true' falls through.

			default:
				throw new InvalidArgumentException('invalid input type');
		}

		//	Then copy each field to the appropriate place.
		foreach ($in as $k => $v) {
			if (!$this->_keyExists($k)) {
				continue;
			}

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

	public function getArrayOptions(): int
	{
		return $this->_arrayOptions->get();
	}

	public function setArrayOptions(int $opts)
	{
		$this->_arrayOptions->set($opts);
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
			foreach ($this->_defaultVars as $k => &$vDefault) {
				$isSA = $vDefault instanceof ScalarAbstract;
				if ($isSA) {
					$v     = $this->{$k}->get();
					$vOrig = $v;
				}
				else {
					$v = &$this->{$k};
				}

				yield $k => $v;

				if ($isSA) {
					if ($v !== $vOrig) {
						$this->{$k}->set($v);
					}
				}
				else {
					$thisType = gettype($vDefault);
					switch ($thisType) {
						case 'bool':
						case 'boolean':
						case 'int':
						case 'integer':
						case 'float':
						case 'double':
						case 'real':
						case 'string':
						case 'resource':
							//	Cast if not the same type.
							if (gettype($this->{$k}) !== $thisType) {
								$this->_setByName($k, $this->{$k});
							}
							break;

						case 'obj':
						case 'cla':
							//	Cast if not the same type.
							if (!is_object($this->{$k}) || get_class($this->{$k}) !== get_class($vDefault)) {
								$this->_setByName($k, $this->{$k});
							}
							break;

						//	Null property types don't get checked.
					}
				}
			}
		})();
	}

	/**
	 * Be sure json_encode get's our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		return $this->toArray();
	}

	/**
	 * String representation of object
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		$toSerialize = ['_arrayOptions' => $this->_arrayOptions->get()];
		foreach ($this->_publicNames as $k) {
			$toSerialize[$k] = $this->{$k};
		}

		return serialize($toSerialize);
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
		$this->_init();

		$data = unserialize($serialized);

		$this->_arrayOptions->set($data['_arrayOptions']);
		unset($data['_arrayOptions']);

		foreach ($data as $k => $v) {
			$this->{$k} = $v;
		}
	}

	/**
	 * Returns an array with all public, protected, and private properties in
	 * object that DO NOT begin with an underscore. This allows protected or
	 * private properties to be treated as if they were public. This supports the
	 * convention that protected and private property names begin with an
	 * underscore (_).
	 *
	 * @return array
	 */
	final public function toArray(): array
	{
		$omitEmpty      = $this->_arrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$omitResource   = $this->_arrayOptions->has(ArrayOptions::OMIT_RESOURCE);
		$switchID       = $this->_arrayOptions->has(ArrayOptions::SWITCH_ID);
		$keepJsonExpr   = $this->_arrayOptions->has(ArrayOptions::KEEP_JSON_EXPR);
		$bsonDate       = $this->_arrayOptions->has(ArrayOptions::TO_BSON_DATE);
		$switchNestedID = $this->_arrayOptions->has(ArrayOptions::SWITCH_NESTED_ID);

		$ZJE_STRING = '\\Zend\\Json\\Expr';

		$arr = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);

			switch (gettype($v)) {
				case 'null':
				case 'NULL':
					if (!$omitEmpty) {
						$arr[$k] = null;
					}
					break;

				case 'resource':
					if (!$omitResource) {
						$arr[$k] = $v;
					}
					break;

				case 'string':
					if ('' !== $v || !$omitEmpty) {
						$arr[$k] = $v;
					}
					break;

				case 'object':
					if (($this->$k instanceof $ZJE_STRING) && $keepJsonExpr) {
						$arr[$k] = $this->$k;    // maintain the type
					}
					elseif ($this->$k instanceof UTCDateTime && $bsonDate) {
						$arr[$k] = $this->$k;    // maintain the type
					}
					elseif ($this->$k instanceof DateTimeInterface && $bsonDate) {
						$dtMilliSeconds = ($this->$k->getTimestamp() * 1000) + (int)$this->$k->format('v');
						$arr[$k]        = new UTCDateTime($dtMilliSeconds);
					}
					elseif (method_exists($v, 'toArray')) {
						if (method_exists($v, 'getArrayOptions')) {
							$vOrigOpts  = $v->getArrayOptions();
							$thisArrOpt = $this->_arrayOptions->get();
							if (!$switchNestedID) {
								if (($vOrigOpts & ArrayOptions::SWITCH_ID) > 0) {
									$thisArrOpt |= ArrayOptions::SWITCH_ID;
								}
								else {
									$thisArrOpt &= ~ArrayOptions::SWITCH_ID;
								}
							}
							$v->setArrayOptions($thisArrOpt);
						}

						$arr[$k] = $v->toArray();

						if (isset($vOrigOpts)) {
							$v->setArrayOptions($vOrigOpts);
							unset($vOrigOpts);
						}

						if (count($arr[$k]) === 0 && $omitEmpty) {
							unset($arr[$k]);
						}
					}
					elseif (method_exists($v, '__toString')) {
						$arr[$k] = $v->__toString();
						if ($arr[$k] === '' && $omitEmpty) {
							unset($arr[$k]);
						}
					}
					else {
						if (count((array)$v) || !$omitEmpty) {
							$arr[$k] = $v;
						}
					}
					break;

				case 'array':
					if (count($v) || !$omitEmpty) {
						$arr[$k] = $v;
					}
					break;

				//	ints and floats
				default:
					$arr[$k] = $v;
			}

			if ($k === 'id_' && $switchID) {
				$arr['_id'] = &$arr['id_'];
				unset($arr['id_']);
			}
		}

		return $arr;
	}

	/**
	 * Sets a variable to it's default value rather than unsetting it.
	 *
	 * @param string $k
	 */
	public function __unset($k)
	{
		$this->{$k} = is_object($this->_defaultVars[$k]) ?
			clone $this->_defaultVars[$k] :
			$this->_defaultVars[$k];
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
	 * @param string $k
	 *
	 * @return bool
	 */
	public function __isset($k): bool
	{
		if ($k[0] === '_') {
			return false;
		}

		return isset($this->{$k});
	}

	/**
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		$origOptions = $this->_arrayOptions->get();
		$this->setArrayOptions(ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::SWITCH_ID | ArrayOptions::TO_BSON_DATE);

//		$arr = array_merge($this->toArray(), ['_arrayOptions' => $origOptions]);
		$arr = $this->toArray();

		$this->_arrayOptions->set($origOptions);

		return $arr;
	}

	/**
	 * Called automatically by MongoDB when a document has a field namaed "__pclass".
	 *
	 * @param array $data
	 */
	public function bsonUnserialize(array $data)
	{
		$this->_init();
		if (array_key_exists('_arrayOptions', $data)) {
			$this->_arrayOptions->set($data['_arrayOptions']);
		}
		$this->assign($data);
	}

	/**
	 * Set data to named variable.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $k
	 * @param mixed  $v
	 *
	 * @throws InvalidArgumentException
	 */
	protected function _setByName($k, $v)
	{
		if (array_key_exists($k, $this->_map)) {
			$k = $this->_map[$k];
		}

		$setter = '_set_' . $k;
		if (method_exists($this->_calledClass, $setter)) {
			$this->$setter($v);

			return;
		}

		//	Get the original type as the current member might contain null.
		switch (gettype($this->_defaultVars[$k])) {
			//	If the original is NULL then allow any value.
			case 'null':
			case 'NULL':
			case '':        //	Is there a possibility that "gettype()" might return an empty string?
			case null:
				$this->{$k} = $v;
				break;

			case 'bool':
			case 'boolean':
				$this->{$k} = Cast::toBoolean($v);
				break;

			case 'int':
			case 'integer':
				$this->{$k} = Cast::toInteger($v);
				break;

			case 'float':
			case 'double':
			case 'real':
				$this->{$k} = Cast::toDouble($v);
				break;

			case 'string':
				$this->{$k} = Cast::toString($v);
				break;

			case 'array':
				$this->{$k} = Cast::toArray($v);
				break;

			case 'object':
				$this->_castToObject($k, $v);
				break;

			default:    //	resource
				$this->{$k} = $v;
				break;
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
	 * Casting to an object type is dependent on original value and input value.
	 *
	 * @param string $k
	 * @param mixed  $v
	 */
	protected function _castToObject($k, $v)
	{
		$propertyDefaultValue = $this->_defaultVars[$k];

		if ($propertyDefaultValue instanceof ScalarAbstract) {
			$this->{$k}->set($v);
			return;
		}

		//	if this->k is a TypedAbstract object and v is any other type
		//		then absorb v or v's properties into this->k's properties
		if ($propertyDefaultValue instanceof TypedInterface) {
			if ($this->{$k} === null) {
				$this->{$k} = clone $propertyDefaultValue; //	cloned for possible default values
			}

			$this->{$k}->assign($v);
			return;
		}

		$propertyClassType = get_class($propertyDefaultValue);

		if (is_object($v)) {
			//	if identical types then reference the original object
			if ($propertyClassType === get_class($v)) {
				$this->{$k} = $v;
			}

			//	Treat DateTime related objects as atomic in these next cases.
			elseif (
				($propertyDefaultValue instanceof DateTimeInterface) && ($v instanceof UTCDateTimeInterface)
			) {
				$this->{$k} = new $propertyClassType($v->toDateTime());
			}
			elseif (
				($propertyDefaultValue instanceof UTCDateTimeInterface) && ($v instanceof DateTimeInterface)
			) {
				$this->{$k} = new $propertyClassType($v->getTimestamp() * 1000);
			}

			//	if this->k is a DateTime object and v is any other type
			//		then absorb v or v's properties into this->k's properties
			//		But only if $v object has __toString.
			elseif ($propertyDefaultValue instanceof DateTimeInterface && method_exists($v, '__toString')) {
				$this->{$k} = new $propertyClassType($v->__toString());
			}

			//	Else give up.
			else {
				throw new InvalidArgumentException('cannot coerce object types');
			}

			return;
		}

		if ($v === null) {
			$this->{$k} = clone $propertyDefaultValue;
		}
		elseif ($propertyClassType === 'stdClass' && is_array($v)) {
			$this->{$k} = (object)$v;
		}
		else {
			//	Other classes might be able to absorb/convert other input,
			//		like «DateTime::__construct("now")» accepts a string.
			$this->{$k} = new $propertyClassType($v);
		}
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
	 * Get variable.
	 *
	 * @param string $k
	 *
	 * @return mixed
	 */
	protected function _getByName($k)
	{
		if ($this->{$k} instanceof ScalarAbstract) {
			return $this->{$k}->get();
		}

		return $this->{$k};
	}

	private function _init()
	{
		$this->_calledClass = get_called_class();

		if (!isset($this->_arrayOptions)) {
			$this->_arrayOptions = new ArrayOptions();
		}

		//	Build array of default values.
		//	First get all class properties then remove elements with names starting with underscore.
		//	Then convert strings with class names into actual instances.
		$this->_defaultVars = get_class_vars($this->_calledClass);
		foreach ($this->_defaultVars as $k => $v) {
			if ($k[0] === '_') {
				unset($this->_defaultVars[$k]);
			}

			//	TODO: Use of "eval" is deprecated.
			//	Change class definition string into a real class for the defaults.
			//	If $v is a string and has '__class__' at the start then instantiate the named object.
			elseif (is_string($v) && 0 === stripos($v, '__class__')) {
				//	We must use `eval` because we want to handle
				//		'__class__Date' and
				//		'__class__DateTime("Jan 1, 2015")' with 1 or more parameters.
				$this->_defaultVars[$k] = eval('return new ' . substr($v, 9) . ';');    //	DEPRECATED

				//	Objects are always passed by reference,
				//		but we want a separate copy so the original stays unchanged.
				$this->{$k} = clone $this->_defaultVars[$k];
			}

			elseif (is_array($v) && isset($v['__type__'])) {
				$propTypeName = $v['__type__'];
				unset($v['__type__']);

				$this->_defaultVars[$k] = new $propTypeName(...$v);
				$this->{$k}             = clone $this->_defaultVars[$k];
			}
		}

		$this->_publicNames = array_keys($this->_defaultVars);
		$this->_count       = count($this->_defaultVars);
	}

	/**
	 * Returns true if key/prop name exists.
	 *
	 * @param string $k
	 *
	 * @return bool
	 */
	private function _keyExists($k): bool
	{
		return array_key_exists($k, $this->_defaultVars) ||
			(array_key_exists($k, $this->_map) && array_key_exists($this->_map[$k], $this->_defaultVars));
	}

}
