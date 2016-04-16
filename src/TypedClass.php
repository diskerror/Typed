<?php
/**
 * Provides support for class members/properties maintain their initial types.
 * @name		TypedClass
 * @copyright	Copyright (c) 2012 Reid Woodbury Jr.
 * @license		http://www.apache.org/licenses/LICENSE-2.0.html	Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Iterator;
use InvalidArgumentException;

/**
 * Create a child of this class with your named properties with a visibility of
 *	  protected or private, and default values of the desired type. Property
 *	  names CANNOT begin with an underscore. This maintains the Zend Framework
 *	  convention that protected and private property names should begin with an
 *	  underscore. This abstract class will expose all members whose name don't
 *	  begin with an underscore, but filter access to those class members or
 *	  properties that have a visibility of protected or private.
 *
 * Input to the constructor or assignObject methods must be an array or object. Only the
 *	  values in the matching names will be filtered and copied into the object.
 *	  All input will be copied by value, not referenced.
 *
 * This class will adds simple casting of input values to be the same type as the
 *	  named property or member. This includes scalar values, built-in PHP classes,
 *	  and other classes, especially those derived from this class.
 *
 * Only properties in the original child class are allowed. This prevents erroneously
 *	  adding properties on the fly.
 *
 * More elaborate filtering can be done by creating methods with this naming
 *	  convention: If property is called "personName" then create a method called
 *	  "_set_personName($in)". That is, prepend "_set_" to the property name.
 *
 * The ideal usage of this abstract class is as the parent class of a data set
 *	  where the input to the constructor (or assignObject) method is an HTTP request
 *	  object. It will help with filtering and insuring the existance of default
 *	  values for missing input parameters.
 */
abstract class TypedClass extends TypedAbstract implements Iterator
{
	/**
	 * Holds the name of the name of the child class for method_exists and property_exists.
	 *
	 * @var string
	 */
	private $_called_class;

	/**
	 * Holds the default values of the called class to-be-public properties in associative array.
	 * @var array
	 */
	private $_class_vars;

	/**
	 * Holds the names of the called class' to-be-public properties in an indexed array.
	 *
	 * @var array
	 */
	private $_public_names;

	/**
	 * Holds the position for Iterator.
	 *
	 * @var int
	 */
	private $_position = 0;

	/**
	 * Holds the name pairs for when different/bad key names need to point to the same data.
	 *
	 * @var array
	 */
	protected $_map = [];

	/**
	 * Constructor.
	 * Accepts an object, array, or JSON string.
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
			//	If $v is a string and has '__class__' at the start then instantiate the named object.
			elseif ( is_string($v) && 0 === stripos($v, '__class__') ) {
				//	We must use `eval` because we want to handle
				//		'__class__Date' and
				//		'__class__DateTime("Jan 1, 2015")' with 1 or more parameters.
				$this->_class_vars[$k] = eval( preg_replace('/^__class__(.*)$/iu', 'return new $1;', $v) );
				//	Objects are always passed by reference,
				//		but we want a separate copy so the original stays unchanged.
				$this->{$k} = clone $this->_class_vars[$k];
			}
		}

		$this->_public_names = array_keys($this->_class_vars);

		//	Don't waste time with assignObject if input is one of these.
		//		Just return leaving the default values.
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
	 * All member objects will be deep cloned.
	 */
	public function __clone()
	{
		foreach ($this->_public_names as $k) {
			if ( is_object($this->{$k}) ) {
				$this->{$k} = clone $this->{$k};
			}
		}
	}

	/**
	 * Returns true if key/prop name exists.
	 *
	 * @param string $k
	 * @return bool
	 */
	private function _keyExists($k)
	{
		return ( array_key_exists($k, $this->_class_vars) || array_key_exists($k, $this->_map) );
	}

	/**
	 * Throws exception if named property does not exist.
	 *
	 * @param string $k
	 * @throws InvalidArgumentException
	 */
	protected function _assertPropName($k)
	{
		if ( !$this->_keyExists($k) ) {
			throw new InvalidArgumentException();
		}
	}

	/**
	 * Copies all matching property names while maintaining original types and
	 *	 doing a deep copy where appropriate.
	 * This method silently ignores extra properties in $input,
	 *	 leaves unmatched properties in this class untouched, and
	 *	 skips names starting with an underscore.
	 * Indexed arrays ARE COPIED BY POSITION starting with the first sudo-public
	 *	property (property names not starting with an underscore). Extra values
	 *	are ignored. Unused properties are unchanged.
	 *
	 * Input can be an object, or an indexed or associative array.
	 *
	 * @param object|array|string|bool|null $in -OPTIONAL
	 */
	public function assignObject($in = null)
	{
		switch ( gettype($in) ) {
			case 'object':
			break;

			case 'array':
			//	Test to see if it's an indexed or an associative array.
			//	Leave associative array as is.
			//	Copy indexed array by position to a named array
			if ( array_values($in) === $in ) {
				$nameArr = $this->_class_vars;
				$minCount = min( count($in), count($nameArr) );
				for ( $i = 0; $i < $minCount; ++$i ) {
					$nameArr[$this->_public_names[$i]] = $in[$i];
				}

				$in = $nameArr;
			}
			break;

			case 'null':
			case 'NULL':
			case 'bool':
			case 'boolean': //	a 'false' is returned by MySQL:PDO for "no results"
			//	So, return default values;
			if ( $in !== true ) {	//	do only if false or null. True does nothing.
				foreach ($this->_class_vars as $k => &$v) {
					$this->__unset($k);
				}

				return;
			}
			//	A boolean 'true' falls through.

			default:
			throw new InvalidArgumentException('invalid input type');
		}

		foreach ($in as $k => $v) {
			if ( !$this->_keyExists($k) ) {
				continue;
			}

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
		if ( array_key_exists($k, $this->_map) ) {
			$k = $this->_map[$k];
		}

		$setter = '_set_' . $k;
		if ( method_exists( $this->_called_class, $setter ) ) {
			$this->$setter($v);

			return;
		}

		//	Get the original type as the current member might contain null.
		switch ( gettype($this->_class_vars[$k]) ) {
			//	If the original is NULL then allow any value.
			case 'null':
			case 'NULL':
			case '':
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
			if ( is_object($v) ) {
				//	if identical types then clone
				if ( get_class($this->_class_vars[$k]) === get_class($v) ) {
					$this->{$k} = clone $v;
				}

				//	if this->k is a TypedAbstract object and v is any other type
				//		then absorb v or v's properties into this->k's properties
				elseif ($this->_class_vars[$k] instanceof TypedAbstract) {
					$this->{$k}->assignObject($v);
				}

				//	Else give up.
				else {
					throw new InvalidArgumentException('cannot coerce object types');
				}
			}
			else {
				if ( get_class($this->_class_vars[$k]) === 'stdClass' && is_array($v) ) {
					$this->{$k} = (object) $v;
				}
				else {
					//	Other classes might be able to absorb/convert other input,
				//		like «DateTime::__construct("now")» accepts a string.
					$class = get_class($this->_class_vars[$k]);
					$this->{$k} = new $class($v);
				}
//				//	Else give up.
//				else {
//					throw new InvalidArgumentException('cannot coerce data into object');
//				}
			}
			break;

			default:	//	resource
			$this->{$k} = $v;
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
		if ( $k[0] === '_' ) {
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
		$this->{$k} = is_object($this->_class_vars[$k]) ?
			clone $this->_class_vars[$k] :
			$this->_class_vars[$k];
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
		return $this->_getByName( $this->_public_names[$this->_position] );
	}

	/**
	 * Required method for Iterator.
	 * @return mixed
	 */
	final public function key()
	{
		return $this->_public_names[$this->_position];
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
	final public function valid()
	{
		return isset( $this->_public_names[$this->_position] );
	}

	/**
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

		foreach ($this->_public_names as $k) {
			$v = $this->_getByName($k);

			if ( is_object($v) ) {
				if ( $this->$k instanceof \Zend\Json\Expr ) {
					$arr[$k] = $this->$k;	// maintain the type
				}
				elseif ( method_exists($v, 'toArray') ) {
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
	 * This is simmilar to "toArray" above except that some conversions are
	 * made to be more compatible to MongoDB. All objects with a lineage
	 * of DateTime are converted to MongoDB\BSON\UTCDateTime, and all top
	 * level members with the name "id_" are assumed to be intended to be a
	 * Mongo primary key and the name is changed to "_id". All times are
	 * assumed to be UTC time. Null or empty members are omitted.
	 *
	 * @return array
	 */
	final public function getMongoObj()
	{
		$arr = [];

		foreach ($this->_public_names as $k) {
			$v = $this->_getByName($k);

			if ( $k === 'id_' ) {
				$arr['_id'] = $v;
				continue;
			}

			if ( is_object($this->$k) ) {
				// if each of these is not empty
				if ( method_exists($this->$k, 'getMongoObj') ) {
					$tObj = $this->$k->getMongoObj();
					if ( count($tObj) ) {
						$arr[$k] = $tObj;
					}
				}
				elseif ( $this->$k instanceof \Zend\Json\Expr || $this->$k instanceof \MongoDB\BSON\UTCDateTime ) {
					$arr[$k] = $this->$k;	// maintain the type
				}
				elseif ( $this->$k instanceof \DateTime ) {
					$arr[$k] = new \MongoDB\BSON\UTCDateTime( $this->$k->getTimestamp()*1000 );
				}
				elseif ( method_exists($v, 'toArray') ) {
					$vArr = $v->toArray();
					if ( count($vArr) ) {
						$arr[$k] = $vArr;
					}
				}
				elseif ( method_exists($v, '__toString') ) {
					$vStr = $v->__toString();
					if ( $vStr !== '' ) {
						$arr[$k] = $vStr;
					}
				}
				else {
					if ( count($v) ) {
						$arr[$k] = $v;
					}
				}

				continue;
			}

			switch ( gettype($v) ) {
				case 'null':
				case 'NULL':
				case 'resource':
				// do not copy
				break;

				case 'string':
				//	Copy only if there is data. Should this only apply to nulls?
				if ( '' !== $v ) {
					$arr[$k] = $v;
				}
				break;

				case 'object':
				if ( method_exists($v, 'toArray') ) {
					$vArr = $v->toArray();
					if ( count($vArr) ) {
						$arr[$k] = $vArr;
					}
				}
				elseif ( method_exists($v, '__toString') ) {
					$vStr = $v->__toString();
					if ( $vStr !== '' ) {
						$arr[$k] = $vStr;
					}
				}
				else {
					if ( count($v) ) {
						$arr[$k] = $v;
					}
				}
				break;

				case 'array':
				if ( count($v) > 0 ) {
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
