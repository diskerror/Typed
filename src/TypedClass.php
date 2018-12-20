<?php
/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           \Diskerror\Typed\TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use DateTimeInterface;
use function in_array;
use InvalidArgumentException;
use MongoDB\BSON\{
	Persistable, UTCDateTime, UTCDateTimeInterface
};
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
abstract class TypedClass extends TypedAbstract implements Persistable
{
	/**
	 * Holds the name pairs for when different/bad key names need to point to the same data.
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Holds options for "toArray" customizations when used by json_encode.
	 *
	 * @var \Diskerror\Typed\ArrayOptions
	 */
	private $_toJsonOptions;

	/**
	 * Holds default options for "toArray" customizations when used by json_encode.
	 *
	 * @var int
	 */
	protected $_toJsonOptionDefaults = ArrayOptions::OMIT_RESOURCE | ArrayOptions::KEEP_JSON_EXPR;

	/**
	 * Holds options for "toArray" customizations when used by MongoDB.
	 *
	 * @var \Diskerror\Typed\ArrayOptions
	 */
	private $_toBsonOptions;

	/**
	 * Holds default options for "toArray" customizations when used by MongoDB.
	 *
	 * @var int
	 */
	protected $_toBsonOptionDefaults = ArrayOptions::OMIT_EMPTY | ArrayOptions::OMIT_RESOURCE | ArrayOptions::OMIT_ID | ArrayOptions::TO_BSON_DATE | ArrayOptions::NO_CAST_BSON_ID;

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
		$this->_initArrayOptions();
		$this->_initProperties();

		switch (gettype($in)) {
			case 'string':
			case 'array':
			case 'object':
				$this->replace($in);
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
			//	"True" falls through and triggers exception.
			//	We allow "false" because some DB frameworks return "false" for empty result sets.

			default:
				throw new InvalidArgumentException('bad value to constructor');
		}
	}

	private function _initArrayOptions()
	{
		$this->_arrayOptions  = new ArrayOptions($this->_arrayOptionDefaults);
		$this->_toJsonOptions = new ArrayOptions($this->_toJsonOptionDefaults);
		$this->_toBsonOptions = new ArrayOptions($this->_toBsonOptionDefaults);
	}

	private function _initProperties()
	{
		$this->_calledClass = get_called_class();

		//	Build array of default values with converted types.
		//	First get all class properties then remove elements with names starting with underscore, except "_id".
		$this->_defaultVars = get_class_vars($this->_calledClass);
		foreach ($this->_defaultVars as $k => &$v) {
			if ($k[0] === '_' && $k !== '_id') {
				unset($this->_defaultVars[$k]);
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
					if (count($v) > 0 && array_values($v) === $v && is_string($v[0]) && class_exists($v[0])) {
						$className = array_shift($v);
						$v         = new $className(...$v);
					}
					else {
						$v = new TypedArray('', $v);
					}
					break;

				default:
					//	Do nothing. Don't try to cast.
			}

			/**
			 * Everything is now an object.
			 * Clone the default/original value back to the original property.
			 */
			$this->{$k} = clone $v;
		}

		$this->_publicNames = array_keys($this->_defaultVars);
		$this->_count       = count($this->_defaultVars);
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

		if (count($in) === 0) {
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
			foreach ($this->_defaultVars as $k => &$vDefault) {
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
	 * Be sure json_encode get's our prepared array.
	 *
	 * @return array
	 */
	public function jsonSerialize()
	{
		$origOptions         = $this->_arrayOptions;
		$this->_arrayOptions = $this->_toJsonOptions;

		$arr = $this->toArray();

		$this->_arrayOptions = $origOptions;

		return $arr;
	}

	/**
	 * String representation of object
	 *
	 * @link  https://php.net/manual/en/serializable.serialize.php
	 * @return string the string representation of the object or null
	 */
	public function serialize(): string
	{
		$toSerialize = [
			'_arrayOptions'  => $this->_arrayOptions,
			'_toJsonOptions' => $this->_toJsonOptions,
			'_toBsonOptions' => $this->_toBsonOptions,
		];
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
		//	Array options have been serialized and do not need initialization.
		$this->_initProperties();

		$data = unserialize($serialized);

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
		$omitEmpty    = $this->_arrayOptions->has(ArrayOptions::OMIT_EMPTY);
		$keepJsonExpr = $this->_arrayOptions->has(ArrayOptions::KEEP_JSON_EXPR);
		$bsonDate     = $this->_arrayOptions->has(ArrayOptions::TO_BSON_DATE);

		$ZJE_STRING = '\\Zend\\Json\\Expr';

		$arr = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);    //	AtomicInterface objects are returned as scalars.

			if ($k === '_id') {
				if ($this->_arrayOptions->has(ArrayOptions::OMIT_ID)) {
					continue;
				}

				if ($this->_arrayOptions->has(ArrayOptions::NO_CAST_BSON_ID)) {
					$arr['_id'] = $v;
					continue;
				}
			}

			switch (gettype($v)) {
				case 'null':
				case 'NULL':
				case 'string':
				case 'array':
					if (!$omitEmpty || !empty($v)) {
						$arr[$k] = $v;
					}
					break;

				case 'resource':
					if (!$this->_arrayOptions->has(ArrayOptions::OMIT_RESOURCE)) {
						$arr[$k] = $v;
					}
					break;

				case 'object':
					if (($this->{$k} instanceof $ZJE_STRING) && $keepJsonExpr) {
						$arr[$k] = $this->{$k};    // maintain the type
					}
					elseif ($this->{$k} instanceof UTCDateTime && $bsonDate) {
						$arr[$k] = $this->{$k};    // maintain the type
					}
					elseif ($this->{$k} instanceof DateTimeInterface && $bsonDate) {
						$dtMilliSeconds = ($this->{$k}->getTimestamp() * 1000) + (int)$this->{$k}->format('v');
						$arr[$k]        = new UTCDateTime($dtMilliSeconds);
					}
					elseif (method_exists($v, 'toArray')) {
						if (method_exists($v, 'getArrayOptions')) {
							$vOrigOpts = $v->getArrayOptions();
							$v->setArrayOptions($this->_arrayOptions->get());
						}

						$arr[$k] = $v->toArray();

						if (isset($vOrigOpts)) {
							$v->setArrayOptions($vOrigOpts);
							unset($vOrigOpts);
						}
					}
					elseif (method_exists($v, '__toString')) {
						$arr[$k] = $v->__toString();
					}
					else {
						$arr[$k] = $v;
					}

					/** For anything that might have been converted to one of the following types: */
					switch (gettype($arr[$k])) {
						case 'null':
						case 'NULL':
						case 'string':
						case 'array':
							if ($omitEmpty && empty($arr[$k])) {
								unset($arr[$k]);
							}
							break;
					}
					break;

				//	ints and floats
				default:
					$arr[$k] = $v;
			}

		}

		return $arr;
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
	 */
	public function replace($in)
	{
		$this->_massageBlockInput($in);

		if (count($in) === 0) {
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
	 * @return TypedClass
	 */
	public function merge($in)
	{
		$ret = clone $this;
		$ret->replace($in);

		return $ret;
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
	private function _massageBlockInput(&$in)
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
				if (array_values($in) === $in) {
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
				if (true !== $in) {
					/** Change false to empty array. */
					$in = [];
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
		$this->{$k} = clone $this->_defaultVars[$k];
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
	 * Called automatically by MongoDB.
	 *
	 * @return array
	 */
	public function bsonSerialize(): array
	{
		$origOptions         = $this->_arrayOptions;
		$this->_arrayOptions = $this->_toBsonOptions;

		$arr = $this->toArray();

		$this->_arrayOptions = $origOptions;

		return $arr;
	}

	/**
	 * Called automatically by MongoDB when a document has a field namaed "__pclass".
	 *
	 * @param array $data
	 */
	public function bsonUnserialize(array $data)
	{
		$this->_initArrayOptions();
		$this->_initProperties();
		$this->assign($data);
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

		/** If the default value is a null then we allow anything. */
		if (null === $this->_defaultVars[$propName]) {
			$this->{$propName} = $in;
			return;
		}

		/** All properties are now handled as objects. */
		$propertyDefaultValue = $this->_defaultVars[$propName];

		//	Handle our two special object types.
		if ($propertyDefaultValue instanceof AtomicInterface) {
			$this->{$propName}->set($in);
			return;
		}

		if ($propertyDefaultValue instanceof TypedAbstract) {
			$this->{$propName}->assign($in);
			return;
		}

		//	Handle for other types of objects.
		$propertyClassType = get_class($propertyDefaultValue);

		if (is_object($in)) {
			//	if identical types then reference the original object
			if ($propertyClassType === get_class($in)) {
				$this->{$propName} = $in;
			}

			//	Treat DateTime related objects as atomic in these next cases.
			elseif (
				($propertyDefaultValue instanceof DateTimeInterface) && ($in instanceof UTCDateTimeInterface)
			) {
				$this->{$propName} = new $propertyClassType($in->toDateTime());
			}
			elseif (
				($propertyDefaultValue instanceof UTCDateTimeInterface) && ($in instanceof DateTimeInterface)
			) {
				$this->{$propName} = new $propertyClassType($in->getTimestamp() * 1000);
			}

			//	if this->k is a DateTime object and v is any other type
			//		then absorb v or v's properties into this->k's properties
			//		But only if $v object has __toString.
			elseif ($propertyDefaultValue instanceof DateTimeInterface && method_exists($in, '__toString')) {
				$this->{$propName} = new $propertyClassType($in->__toString());
			}

			//	Else give up.
			else {
				throw new InvalidArgumentException('cannot coerce object types');
			}
		}
		else {
			//	Then $v is not an object.
			if ($in === null) {
				$this->{$propName} = clone $propertyDefaultValue;
			}
			elseif ($propertyClassType === 'stdClass' && is_array($in)) {
				$this->{$propName} = (object)$in;
			}
			else {
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				$this->{$propName} = new $propertyClassType($in);
			}
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
	 * Get variable.
	 *
	 * @param string $k
	 *
	 * @return mixed
	 */
	protected function _getByName($k)
	{
		if (array_key_exists($k, $this->_map)) {
			$k = $this->_map[$k];
		}

		$getter = '_get_' . $k;
		if (method_exists($this->_calledClass, $getter)) {
			return $this->{$getter}($v);
		}

		if ($this->{$k} instanceof AtomicInterface) {
			return $this->{$k}->get();
		}

		return $this->{$k};
	}

	/**
	 * Returns true if key/prop name exists or is mappable.
	 * Checks for entry to exist in _map but is mapped to nothing.
	 *
	 * @param string $k
	 *
	 * @return bool
	 */
	private function _keyExists($k): bool
	{
		if (array_key_exists($k, $this->_map)) {
			$k = $this->_map[$k];
		}

		return in_array($k, $this->_publicNames);
	}

}
