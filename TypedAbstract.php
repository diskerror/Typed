<?php

namespace Typed;

require_once 'TypedInterface.php';

use Iterator, Countable;

/**
 * Provides support for class members/properties maintain their initial types.
 *
 * Create a child of this class with your named properties with a visibility of
 *    protected or private, and default values of the desired type. Property
 *    names CANNOT begin with an underscore. This maintains the Zend Framework
 *    convention that protected and private property names should begin with an
 *    underscore. This abstract class will expose all members whose name don't
 *    begin with an underscore, but filter access to those class members or
 *    properties that have a visibility of protected or private.
 *
 * Input to the constructor or assignObject methods must be an array or object. Only the
 *    values in the matching names will be filtered and copied into the object.
 *    All input will be copied by value, not referenced.
 *
 * This class will adds simple casting of input values to be the same type as the
 *    named property or member. This includes scalar values, built-in PHP classes,
 *    and other classes derived from this class.
 *
 * Only properties in the original child class are allowed. This prevents adding
 *    properties on the fly.
 *
 * More elaborate filtering can be done by creating methods with this naming
 *    convention: If property is called "personName" then create a method called
 *    "_set_personName($in)". That is, prepend "_set_" to the property name.
 *
 * The ideal usage of this abstract class is as the parent class of a data set
 *    where the input to the constructor (or assignObject) method is an HTTP request
 *    object. It will help with filtering and insuring the existance of default
 *    values for missing input parameters.
 *
 * @copyright  Copyright (c) 2012 Reid Woodbury Jr.
 * @license    http://www.apache.org/licenses/LICENSE-2.0.html  Apache License, Version 2.0
 */
abstract class TypedAbstract implements TypedInterface, Iterator, Countable
{
	/**
	 * Holds the name of the name of the child class for method_exists and property_exists.
	 * @var string
	 */
	protected $_called_class;

	/**
	 * Holds the default values of the called class to-be-public properties in associative array.
	 * @var array
	 */
	protected $_class_vars;

	/**
	 * Holds the names of the called class' to-be-public properties in an indexed array.
	 * @var array
	 */
	protected $_class_props;

	/**
	 * Holds the position for Iterator.
	 * @var int
	 */
	private $_position = 0;

	/**
	 * Constructor.
	 * Accepts an object, associative array, or JSON string.
	 *
	 * @param mixed $in -OPTIONAL
	 */
	public function __construct($in = null)
	{
		$this->_called_class = get_called_class();
		$this->_class_vars = get_class_vars($this->_called_class);
		//	remove elements with names starting with underscore
		foreach ( $this->_class_vars as $k => $v ) {
			if ($k[0] === '_') {
				unset($this->_class_vars[$k]);
			}
		}

		$this->_class_props = array_keys($this->_class_vars);

		//	Don't waste time with assignObject if input is one of these.
		//		Just return leave the default values.
		switch (gettype($in)) {
			case 'NULL':
			case 'null':
			case 'bool':
			case 'boolean':
			return;
		}

		$this->assignObject( $in );
	}

	/**
	 * Clone.
	 * Objects of type "TypedAbstract" will be deep cloned.
	 */
	public function __clone()
	{
		foreach ($this->_class_props as $k) {
			if ( is_object($this->{$k}) ) {
				$this->{$k} = clone $this->{$k};
			}
		}
	}

	/**
	 * Throws exception if named property does not exist.
	 *
	 * @param string $k
	 * @throws InvalidArgumentException
	 */
	protected function _assertPropName($k)
	{
		if ( !array_key_exists($k, $this->_class_vars) ) {
			throw new InvalidArgumentException();
		}
	}

	/**
	 * Copies all matching property names while maintaining original types and
	 *   doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $obj,
	 *   leaves unmatched properties in this class untouched, and
	 *   skips names starting with an underscore.
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 *	property (property names not starting with an underscore). Extra values
	 *	are ignored. Unused properties are unchanged.
	 *
	 * Input can be an object, an associative array, or
	 *   a JSON string representing an object.
	 *
	 * @param object|array|string|bool|null $in -OPTIONAL
	 * @throws UnexpectedValueException
	 */
	public function assignObject($in = null)
	{
		switch ( gettype($in) ) {
			case 'object':
			break;

			case 'array':
			//	Test to see if it's an indexed or an associative array.
			//	Leave associative array as is.
			for ( reset($in); is_int(key($in)); next($in) );

			//	if it's now null then the array has only integer keys:
			//		then copy by position to a named array
			if ( is_null(key($in)) ) {
				$nameArr = $this->_class_vars;
				$ct = min( count($in), count($nameArr) );
				for ( $i = 0; $i<$ct; ++$i ) {
					$nameArr[$this->_class_props[$i]] = $in[$i];
				}

				$in = $nameArr;
			}
			break;

			case 'string':
			require_once 'Zend/Json.php';
			//	Zend_Json throws an exception if input cannot be interpreted as a JSON string.
			$in = Zend_Json::decode( $in, Zend_Json::TYPE_ARRAY );
			if ( !is_array($in) ) {
				$in = [];
			}
			break;

			case 'null':
			case 'NULL':
			case 'bool':
			case 'boolean':	//	a 'false' is returned by MySQL:PDO for "no results"
			//	return default values;
			if ( $in !== true ) {	//	do only if false or null. True does nothing.
				foreach ($this->_class_vars as $k => $v) {
					$this->{$k} = $v;
				}
			}

			return;

			default:
			throw new InvalidArgumentException('unknown input type');
		}

		foreach ($in as $k => $v) {
			$this->_setByName($k, $v);
		}

		$this->_checkRelatedProperties();
	}

	/**
	 * Set data to named variable.
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $k
	 * @param mixed $v
	 * @throws InvalidArgumentException
	 */
	protected function _setByName($k, $v)
	{
		if ( !array_key_exists($k, $this->_class_vars) ) {
			return;
		}

		$setter = '_set_' . $k;
		if ( method_exists( $this->_called_class, $setter ) ) {
			$this->$setter($v);
			return;
		}

		switch ( gettype($this->_class_vars[$k]) ) {
			case 'bool':
			case 'boolean':
			$this->{$k}	= (boolean) $v;
			break;

			case 'int':
			case 'integer':
			//	if it's a string then assume it might need to be converted
			//	http://php.net/manual/en/function.intval.php
			if ( gettype($v) === 'string' ) {
				$this->{$k} = intval($v, 0);
			}
			else {
				$this->{$k} = (integer) $v;
			}
			break;

			case 'float':
			case 'double':
			$this->{$k}	= (double) $v;
			break;

			case 'string':
			$this->{$k}	= (string) $v;
			break;

			case 'array':
			$this->{$k} = (array) $v;
			break;

			case 'object':
			if ( gettype($v) === 'object' ) {
				//	if identical types then clone
				if ( get_class($this->_class_vars[$k]) === get_class($v) ) {
					$this->{$k} = clone $v;
				}

				//	if this->k is a TypedAbstract object and v is any other type
				//		then absorb v or v's properties into this->k's properties
				elseif ($this->_class_vars[$k] instanceof TypedInterface) {
					$this->{$k}->assignObject($v);
				}

				//	Else give up.
				else {
					throw new InvalidArgumentException('cannot coerce object types');
				}
			}
			else {
				//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
				//	This works for DateTime, UDateTime, and UDate.
				if ( ($this->_class_vars[$k] instanceof DateTime) && gettype($v) === 'string' || is_null($v) ) {
					$class = get_class($this->_class_vars[$k]);
					$this->{$k} = new $class($v);
				}
				//	Else give up.
				else {
					throw new InvalidArgumentException('cannot coerce data into object');
				}
			}
			break;

			case 'null':
			case 'NULL':
			$this->__unset($k); //	set to the default value
			break;

			//	resource
			default:
			$this->{$k}	= $v;
			break;
		}
	}

	/**
	 * Get variable.
	 * @param string $k
	 * @return mixed
	 */
	protected function _getByName($k)
	{
		//	Create a method with a name like the next line and it will be called here.
		$getter = '_get_' . $k;
		if ( method_exists($this->_called_class, $getter ) ) {
			return $this->$getter();
		}

		return $this->{$k};
	}

	/**
	 * Set variable
	 * Casts the incoming data ($v) to the same type as the named ($k) property.
	 *
	 * @param string $k
	 * @param mixed $v
	 * @throws InvalidArgumentException
	 */
	public function __set($k, $v)
	{
		$this->_assertPropName($k);
		$this->_setByName($k, $v);
		$this->_checkRelatedProperties();
	}

	/**
	 * Get variable.
	 * @param string $k
	 * @return mixed
	 */
	public function __get($k)
	{
		$this->_assertPropName($k);
		return $this->_getByName($k);
	}

	/**
	 * Is a variable set?
	 *
	 * @param string $k
	 * @return bool
	 */
	public function __isset($k)
	{
		if ( substr($k, 0, 1) === '_' ) {
			return false;
		}

		return isset($this->{$k});
	}

	/**
	 * Sets a variable to it's default value rather than unsetting it.
	 *
	 * @param string $k
	 */
	public function __unset($k)
	{
		//	rather than unsetting, we set to default value
		$this->{$k} = $this->_class_vars[$k];
	}

	/**
	 * Override this method for additional checking such as when a start date
	 * is required to be earlier than an end date, any range of values like
	 * minimum and maximum, or any custom filtering.
	 */
	protected function _checkRelatedProperties()
	{
	}

	/*
	* Required method for Iterator.
	*/
	final public function rewind()
	{
		$this->_position = 0;
	}

	/*
	* Required method for Iterator.
	 * @return mixed
	*/
	final public function current()
	{
		return $this->_getByName( $this->_class_props[$this->_position] );
	}

	/*
	* Required method for Iterator.
	 * @return mixed
	*/
	final public function key()
	{
		return $this->_class_props[$this->_position];
	}

	/*
	* Required method for Iterator.
	*/
	final public function next()
	{
		++$this->_position;
	}

	/*
	* Required method for Iterator.
	 * @return boolean
	*/
	final public function valid()
	{
		return isset( $this->_class_props[$this->_position] );
	}

	/*
	* Required method for Countable.
	 * @return int
	*/
	final public function count()
	{
		return count( $this->_class_vars );
	}

	/**
	 * Returns an array with all public, protected, and private properties in
	 * object that DO NOT begin with an underscore. This allows protected or
	 * private properties to be treated as if they were public. This supports the
	 * convention that protected and private property names begin with an
	 * underscore (_). Use "__get" and "__set" to access individual names.
	 *
	 * @return array
	 */
	final public function toArray()
	{
		$arr = [];

		foreach ($this->_class_props as $k) {
			$v = $this->_getByName($k);

			if ( is_object($v) ) {
				if ( method_exists($v, 'toArray') ) {
					$arr[$k] = $v->toArray();
				}
				elseif ( method_exists($v, '__toString') ) {
					$arr[$k] = $v->__toString();
				}
				else {
					$arr[$k] = (array) $v;
				}
			}
			else {
				$arr[$k] = $v;
			}
		}

		return $arr;
	}

	/**
	 * Returns JSON string representing the object.
	 * Optionally retruns a pretty-print string.
	 *
	 * @param bool $pretty -OPTIONAL
	 * @return string
	 */
	final public function toJson($pretty = false)
	{
		$j = Zend_Json::encode( $this->toArray() );

		if ( $pretty ) {
			return Zend_Json::prettyprint( $j ) . "\n";
		}

		return $j;
	}

	/**
	 * Returns a string formatted for an SQL insert or update.
	 *
	 * Accepts an array where the values are the names of properties.
	 * An empty array means to use all
	 *
	 * @param array $include
	 * @return string
	 */
	public function getSqlIns(array $include = [])
	{
		if ( count($include) ) {
			$tmp = $this->toArray();
			$arr = [];
			foreach ( $include as $i ) {
				if ( array_key_exists($i, $this->_class_vars) ) {
					$arr[$i] = $tmp[$i];
				}
			}
		}
		else {
			$arr = $this->toArray();
		}

		$sqlStrs = [];
		foreach ($arr as $k => &$v) {
			switch ( gettype($v) ) {
				case 'bool':
				case 'boolean':
				$sqlStrs[] = '`' . $k . '` = ' . ( $v ? 1 : 0 );
				break;

				case 'int':
				case 'integer':
				case 'float':
				case 'double':
				$sqlStrs[] = '`' . $k . '` = ' . $v;
				break;

				case 'string':
				if ( $v === 'NULL' ) {
					$sqlStrs[] = '`' . $k . '` = NULL';
				}
				else {
// 					$sqlStrs[] = '`' . $k . '` = "' . preg_replace('/([\x00\n\r\\\\\'"\x1a])/u', '\\\\$1', $v); . '"';
					$sqlStrs[] = '`' . $k . '` = "' . addslashes($v) . '"';
				}
				break;

				case 'null':
				case 'NULL':
				$sqlStrs[] = '`' . $k . '` = NULL';
				break;

				case 'array':
				case 'object':	//	toArray prevents objects from getting here
				$sqlStrs[] = '`' . $k . '` = 0x' . bin2hex(Zend_Json::encode($v)) . '';
				break;

				//	resource, (just ignore these?)
				default:
				throw new InvalidArgumentException('bad input type');
			}
		}

		return implode(",\n", $sqlStrs);
	}

	/**
	 * Returns a string formatted for an SQL
	 * "ON DUPLICATE KEY UPDATE" statement.
	 *
	 * Accepts an array where the values are the names of properties.
	 * An empty array means to use all
	 *
	 * @param array $include
	 * @return string
	 */
	public function getSqlVals(array $include = [])
	{
		$sqlStrs = [];

		if ( count($include) ) {
			foreach ($include as $i) {
				if ( array_key_exists($i, $this->_class_vars) ) {
					$sqlStrs[] = '`' . $i . '` = VALUES(`' . $i . '`)';
				}
			}
		}
		else {
			foreach ($this->_class_props as $k) {
				$sqlStrs[] = '`' . $k . '` = VALUES(`' . $k . '`)';
			}
		}

		return implode(",\n", $sqlStrs);
	}
	
}
