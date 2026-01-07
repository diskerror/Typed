<?php

/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Diskerror\Typed\AtMap;
use InvalidArgumentException;
use ReflectionObject;
use Traversable;
use TypeError;

/**
 * This requires PHP version 8.2 or greater.
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
     * Cache for property metadata indexed by class name.
     *
     * @var array
     */
    private static array $_metaCache = [];

    /**
     * Cache for property mapping indexed by class name.
     *
     * @var array
     */
    private static array $_mapCache = [];

    /**
     * List of variable types that can contain only one value.
     *
     * @const SINGULAR_NAMES
     */
    public const SINGULAR_NAMES =
        ['', 'boolean', 'bool', 'int', 'integer', 'float', 'double', 'string', 'resource', 'callable'];

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
    public function __construct(mixed $in = [])
    {
        $this->conversionOptions = new ConversionOptions();
        $this->_initProperties();

        $className = static::class;

        if (!isset(self::$_metaCache[$className])) {
            $reflection = new ReflectionObject($this);
            self::$_metaCache[$className] = [];
            self::$_mapCache[$className] = [];

            foreach ($reflection->getProperties() as $rProp) {
                $pName      = $rProp->getName();
                $typeObj    = $rProp->getType();
                $typeName   = !is_null($typeObj) ? $typeObj->__toString() : '';
                $allowsNull = str_starts_with($typeName, '?') || ($typeObj !== null && $typeObj->allowsNull());
                $typeName   = str_starts_with($typeName, '?') ? substr($typeName, 1) : $typeName;
                $isObject   = !self::_isAssignable($typeName);

                if ((str_starts_with($pName, '_') && $pName !== '_id') || $pName === 'conversionOptions') {
                    continue;
                }

                self::$_metaCache[$className][$pName] = new PropertyMetaData(
                    $typeName,
                    $isObject,
                    $allowsNull,
                    $rProp->isPublic()
                );

                // Check for Map attribute
                $attributes = $rProp->getAttributes(AtMap::class);
                foreach ($attributes as $attribute) {
                    $inst = $attribute->newInstance();
                    self::$_mapCache[$className][$inst->name] = $pName;
                }
            }
        }

        $this->_meta = self::$_metaCache[$className];

        // Merge mapped attributes into the local map.
        // Attribute mappings take precedence over class property defined mappings if duplicates exist.
        if (!empty(self::$_mapCache[$className])) {
            $this->_map = array_merge($this->_map, self::$_mapCache[$className]);
        }

        foreach ($this->_meta as $pName => $meta) {
            if (!isset($this->$pName)) {
                //  Always instantiate objects. They cannot be null.
                if ($meta->isObject) {
                    $pType = $meta->type;
                    $this->$pName = new $pType();
                }
                elseif (!$meta->isNullable) {
                    if (in_array($meta->type, self::SINGULAR_NAMES)) {
                        $tmp = '';
                        settype($tmp, $meta->type);
                        $this->$pName = $tmp;
                    }
                    elseif ($meta->type === 'array') {
                        $this->$pName = [];
                    }
                }
            }
        }

        if ($in !== []) {
            $this->assign($in);
        }
    }

    /**
     * Initialize properties.
     *
     * All properties that are objects will be instantiated.
     * All properties that are scalars or arrays will be initialized to zero or empty or left null.
     *
     * Either override constructor and call constructor parent, or override this method for your initializations.
     */
    protected function _initProperties(): void
    {
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
    public function setConversionOptionsToNested(): void
    {
        foreach ($this->getPublicNames() as $k) {
            if ($this->$k instanceof TypedAbstract) {
                $this->$k->conversionOptions->set($this->conversionOptions->get());
                $this->$k->setConversionOptionsToNested();
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
        $dateToString    = $this->conversionOptions->isset(ConversionOptions::DATE_TO_STRING);
        $objectsToString = $this->conversionOptions->isset(ConversionOptions::ALL_OBJECTS_TO_STRING);
        $omitResources   = $this->conversionOptions->isset(ConversionOptions::OMIT_RESOURCE);
        $omitEmpty       = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);

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

                    case is_a($v, DateTime::class, true):
                        $v = $dateToString ? (string)$v : (array)$v;
                        break;

                    case $objectsToString
                        && method_exists($v, '__toString')
                        && !is_a($v, DateTime::class, true):
                        $v = (string)$v;
                        break;

                    default:
                        $v = (array)$v;
                }
            }
            elseif ($omitResources && is_resource($v)) {
                continue;
            }

            //	Testing for empty must happen after nested objects have been reduced.
            if ($omitEmpty && empty($v)) {
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
        $keepJsonExpr = $this->conversionOptions->isSet(ConversionOptions::KEEP_JSON_EXPR);
        $ZJE          = '\\Laminas\\Json\\Expr';
        $omitEmpty    = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);

        $arr = $this->toArray();
        foreach ($this->_meta as $k => $meta) {
            if ($meta->isObject) {
                switch (true) {
                    case method_exists($this->$k, 'jsonSerialize'):
                        $arr[$k] = $this->$k->jsonSerialize();
                        break;

                    case $keepJsonExpr && $this->$k instanceof $ZJE:
                        $arr[$k] = $this->$k;  // return as \Laminas\Json\Expr
                        break;

                    default:
                }

                if ($omitEmpty && isset($arr[$k]) && empty((array)$arr[$k])) {
                    unset($arr[$k]);
                }
            }

            //	Testing for empty must happen after nested objects have been reduced.
            if ($omitEmpty && isset($arr[$k]) && empty($arr[$k])) {
                unset($arr[$k]);
            }
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
            $pn       = $this->getPublicNames();
            for ($i = 0; $i < $minCount; ++$i) {
                $newArr[$pn[$i]] = $in[$i];
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

                    case in_array($pType, self::SINGULAR_NAMES, true):
                        $this->$pName = null;
                        break;

                    case $pType === 'array':
                        $this->$pName = [];
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

            case in_array($pType, self::SINGULAR_NAMES, true):
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
