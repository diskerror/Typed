<?php
/**
 * Provides support for class members/properties maintain their initial types.
 * @name        TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Iterator;
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
 * Input to the constructor or assignObject methods must be an array or object. Only the
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
 *      where the input to the constructor (or assignObject) method is an HTTP request
 *      object. It will help with filtering and insuring the existance of default
 *      values for missing input parameters.
 */
abstract class TypedClass extends TypedAbstract implements Iterator
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
	 * Holds the position for Iterator.
	 *
	 * @var int
	 */
	private $_position = 0;

	/**
	 * Constructor.
	 * Accepts an object, array, or JSON string.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = null)
	{
		$this->_calledClass = get_called_class();

		//	Build array of default values.
		//	First get all class properties then remove elements with names starting with underscore.
		//	Then convert strings with class names into actual instances.
		$this->_defaultVars = get_class_vars($this->_calledClass);
		foreach ($this->_defaultVars as $k => $v) {
			if ($k[0] === '_') {
				unset($this->_defaultVars[$k]);
			}

			//	Change class definition string into a real class for the defaults.
			//	If $v is a string and has '__class__' at the start then instantiate the named object.
			elseif (is_string($v) && 0 === stripos($v, '__class__')) {
				//	We must use `eval` because we want to handle
				//		'__class__Date' and
				//		'__class__DateTime("Jan 1, 2015")' with 1 or more parameters.
//				$this->_defaultVars[$k] = eval( preg_replace('/^__class__(.*)$/iu', 'return new $1;', $v) );
				$this->_defaultVars[$k] = eval('return new ' . substr($v, 9) . ';');

				//	Objects are always passed by reference,
				//		but we want a separate copy so the original stays unchanged.
				$this->{$k} = clone $this->_defaultVars[$k];
			}
		}

		$this->_publicNames = array_keys($this->_defaultVars);

		//	Don't waste time with assignObject if input is one of these.
		//		Just return leaving the default values.
		switch (gettype($in)) {
			case 'NULL':
			case 'null':
			case 'bool':
			case 'boolean':
				return;
		}

		$this->assignObject($in);
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
	public function assignObject($in = null)
	{
		switch (gettype($in)) {
			case 'object':
			break;

			case 'array':
				//	Test to see if it's an indexed or an associative array.
				//	Leave associative array as is.
				//	Copy indexed array by position to a named array
				if (array_values($in) === $in) {
					$nameArr = $this->_defaultVars;
					$minCount = min(count($in), count($nameArr));
					for ($i = 0; $i < $minCount; ++$i) {
						$nameArr[$this->_publicNames[$i]] = $in[$i];
					}

					$in = $nameArr;
				}
			break;

			case 'string':
				$in = json_decode($in);
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

		foreach ($in as $k => $v) {
			if (!$this->_keyExists($k)) {
				continue;
			}

			$this->_setByName($k, $v);
		}

		$this->_checkRelatedProperties();
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
	 * Returns true if key/prop name exists.
	 *
	 * @param string $k
	 *
	 * @return bool
	 */
	private function _keyExists($k) : bool
	{
		return array_key_exists($k, $this->_defaultVars) || array_key_exists($k, $this->_map);
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
	protected function _setByName($k, &$v)
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
				$this->{$k} = self::_castToBoolean($v);
			break;

			case 'int':
			case 'integer':
				$this->{$k} = self::_castToInteger($v);
			break;

			case 'float':
			case 'double':
			case 'real':
				$this->{$k} = self::_castToDouble($v);
			break;

			case 'string':
				$this->{$k} = self::_castToString($v);
			break;

			case 'array':
				$this->{$k} = self::_castToArray($v);
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
	 * Casting to an object type that is dependent on original value and input value.
	 *
	 * @param string $k
	 * @param mixed  $v
	 */
	protected function _castToObject($k, &$v)
	{
		$propertyDefaultClass = $this->_defaultVars[$k];
		$propertyClassType = get_class($propertyDefaultClass);

		//	if this->k is a TypedAbstract object and v is any other type
		//		then absorb v or v's properties into this->k's properties
		if ($propertyDefaultClass instanceof TypedAbstract) {
			if ($this->{$k} === null) {
				$this->{$k} = clone $propertyDefaultClass; //	cloned for possible default values
			}

			$this->{$k}->assignObject($v);
		}

		elseif (is_object($v)) {
			//	if identical types then clone
			if ($propertyClassType === get_class($v)) {
				$this->{$k} = clone $v;
			}

			//	Treat DateTime related objects as atomic in these next cases.
			elseif (
				($propertyDefaultClass instanceof \DateTimeInterface) && ($v instanceof \MongoDB\BSON\UTCDateTimeInterface)
			) {
				$this->{$k} = new $propertyClassType($v->toDateTime());
			}
			elseif (
				($propertyDefaultClass instanceof \MongoDB\BSON\UTCDateTimeInterface) && ($v instanceof \DateTimeInterface)
			) {
				$this->{$k} = new $propertyClassType($v->getTimestamp() * 1000);
			}

			//	if this->k is a DateTime object and v is any other type
			//		then absorb v or v's properties into this->k's properties
			//		But only if $v object has __toString.
			elseif ($propertyDefaultClass instanceof \DateTimeInterface && method_exists($v, '__toString')) {
				$this->{$k} = new $propertyClassType($v->__toString());
			}

			//	Else give up.
			else {
				throw new InvalidArgumentException('cannot coerce object types');
			}
		}

		else {
			if ($v === null) {
				$this->{$k} = clone $propertyDefaultClass;
			}
//			elseif($propertyDefaultClass instanceof TypedArray){
//				$this->{$k}[] = $v;
//			}
			elseif ($propertyClassType === 'stdClass' && is_array($v)) {
				$this->{$k} = (object)$v;
			}
			else {
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				$this->{$k} = new $propertyClassType($v);
			}
		}
	}

	/**
	 * Override this method for additional checking such as when a start date
	 * is required to be earlier than an end date, any range of values like
	 * minimum and maximum, or any custom filtering not dependent on a single property.
	 */
	protected function _checkRelatedProperties()
	{
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
		//	Create a method with a name like the next line and it will be called here.
		$getter = '_get_' . $k;
		if (method_exists($this->_calledClass, $getter)) {
			return $this->$getter();
		}

		return $this->{$k};
	}

	/**
	 * Is a variable set?
	 *
	 * @param string $k
	 *
	 * @return bool
	 */
	public function __isset($k) : bool
	{
		if ($k[0] === '_') {
			return false;
		}

		return isset($this->{$k});
	}

	/**
	 * Required method for Iterator.
	 */
	final public function rewind()
	{
		$this->_position = 0;
	}

	/**
	 * Required method for Iterator.
	 * @return mixed
	 */
	final public function current()
	{
		return $this->_getByName($this->_publicNames[$this->_position]);
	}

	/**
	 * Required method for Iterator.
	 * @return mixed
	 */
	final public function key()
	{
		return $this->_publicNames[$this->_position];
	}

	/**
	 * Required method for Iterator.
	 */
	final public function next()
	{
		++$this->_position;
	}

	/**
	 * Required method for Iterator.
	 * @return bool
	 */
	final public function valid() : bool
	{
		return isset($this->_publicNames[$this->_position]);
	}

	/**
	 * Required method for Countable.
	 * @return int
	 */
	final public function count() : int
	{
		return count($this->_defaultVars);
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
	final public function toArray() : array
	{
		$arr = [];

		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);

			if (is_object($v)) {
				if (method_exists($v, 'toArray')) {
					$arr[$k] = $v->toArray();
				}
				elseif (method_exists($v, '__toString')) {
					$arr[$k] = $v->__toString();
				}
				else {
					$arr[$k] = (array)$v;
				}
			}
			else {
				$arr[$k] = $v;
			}
		}

		return $arr;
	}

	/**
	 * This is simmilar to "toArray" above except that some conversions are
	 * made to be more compatible to MongoDB. All objects with a lineage
	 * of DateTime are converted to MongoDB\BSON\UTCDateTime, and all top
	 * level members with the name "id_" are assumed to be intended to be a
	 * Mongo primary key and the name is changed to "_id". All times are
	 * assumed to be UTC time. Null or empty members are omitted.
	 *
	 * @param array $opts
	 *
	 * @return array
	 */
	final public function getSpecialArr(array $opts = []) : array
	{
		//	Options that are passed in overwrite hard coded options.
		$opts = array_merge(
			['dateToBsonDate' => true, 'keepJsonExpr' => true, 'switch_id' => true, 'omitEmpty' => true],
			$opts
		);
		extract($opts, EXTR_OVERWRITE, '');

		static $ZJEstring = '\\Zend\\Json\\Expr';

		$arr = [];
		foreach ($this->_publicNames as $k) {
			$v = $this->_getByName($k);

			if ($k === 'id_' && $_switch_id) {
				$arr['_id'] = $v;
				continue;
			}

			if (is_object($this->$k)) {
				// if each of these is not empty
				if (method_exists($this->$k, 'getSpecialObj')) {
					$tObj = $this->$k->getSpecialObj($opts);
					if (count($tObj)) {
						$arr[$k] = $tObj;
					}
				}
				elseif (($this->$k instanceof $ZJEstring) && $_keepJsonExpr) {
					$arr[$k] = $this->$k;    // maintain the type
				}
				elseif ($this->$k instanceof \MongoDB\BSON\UTCDateTime && $_dateToBsonDate) {
					$arr[$k] = $this->$k;    // maintain the type
				}
				elseif ($this->$k instanceof \DateTimeInterface && $_dateToBsonDate) {
					$arr[$k] = new \MongoDB\BSON\UTCDateTime($this->$k->getTimestamp() * 1000);
				}
				elseif (method_exists($v, 'toArray')) {
					$vArr = $v->toArray();
					if (count($vArr) || !$_omitEmpty) {
						$arr[$k] = $vArr;
					}
				}
				elseif (method_exists($v, '__toString')) {
					$vStr = $v->__toString();
					if ($vStr !== '' || !$_omitEmpty) {
						$arr[$k] = $vStr;
					}
				}
				else {
					if (count($v) || !$_omitEmpty) {
						$arr[$k] = $v;
					}
				}

				continue;
			}

			switch (gettype($v)) {
				case 'null':
				case 'NULL':
					if (!$_omitEmpty) {
						$arr[$k] = NULL;
					}
				break;

				case 'resource':
					// do not copy
				break;

				case 'string':
					//	Copy only if there is data. Should this only apply to nulls?
					if ('' !== $v || !$_omitEmpty) {
						$arr[$k] = $v;
					}
				break;

				case 'object':
					if (method_exists($v, 'toArray')) {
						$vArr = $v->toArray();
						if (count($vArr) || !$_omitEmpty) {
							$arr[$k] = $vArr;
						}
					}
					elseif (method_exists($v, '__toString')) {
						$vStr = $v->__toString();
						if ($vStr !== '' || !$_omitEmpty) {
							$arr[$k] = $vStr;
						}
					}
					else {
						if (count($v) || !$_omitEmpty) {
							$arr[$k] = $v;
						}
					}
				break;

				case 'array':
					if (count($v) || !$_omitEmpty) {
						$arr[$k] = $v;
					}
				break;

				//	ints and floats
				default:
					$arr[$k] = $v;
			}
		}

		return $arr;
	}
}
