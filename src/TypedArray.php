<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use ArrayAccess;
use InvalidArgumentException;
use LengthException;
use Traversable;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends TypedAbstract implements ArrayAccess
{
    /**
     * A string that specifies the type of values in the container.
     * A child class can override _type rather than it being set with the constructor.
     *
     * @var string
     */
    protected string $_type = '';

    /**
     * An array that contains the items of interest.
     *
     * @var array
     */
    protected array $_container = [];

    /**
     * Constructor.
     *
     * If this class is instantiated directly, ie. "$a = new TypedArray('integer', [1, 2, 3]);",
     * then $param1 must be the data type as a string, and then $param2 can be the initial data.
     *
     * If a derived class is instantiated then the data type must be contained in
     * the class, ie. "protected $_type = 'integer';", and $param1 can be the initial data.
     *
     * @param mixed             $param1 OPTIONAL null
     * @param array|object|null $param2 OPTIONAL null
     *
     * @throws InvalidArgumentException
     */
    public function __construct(mixed $param1 = null, mixed $param2 = null)
    {
        $this->conversionOptions = new ConversionOptions();

        if (get_called_class() === self::class) {
            $this->_type = is_string($param1) ? $param1 : '';
            $this->assign($param2);
        }
        else {
            if (!isset($this->_type)) {
                throw new InvalidArgumentException('$this->_type must be set in child class.');
            }

            if (null !== $param2) {
                throw new InvalidArgumentException('Only the first parameter can be set when using a derived class.');
            }

            $this->assign($param1);
        }
    }

    /**
     * Set the array to empty.
     */
    public function clear(): void
    {
        $this->_container = [];
    }

    /**
     * Copies all members into this class. Indexed keys will be re-indexed.
     *
     * @param object|array|string|null $in
     */
    public function assign($in): void
    {
        if ($in === null) {
            $this->_container = [];
        }
        else {
            $this->_massageInput($in);
            foreach ($in as $k => $v) {
                $this->offsetSet($k, $v);
            }
        }
    }

    /**
     * Required by the Countable interface.
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->_container);
    }

    /**
     * Required by the IteratorAggregate interface.
     * Every value is checked for change during iteration.
     *
     * This will return a reference to a scalar value, even if it has a
     * wrapper with the interface AtomicInterface.
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        if (is_a($this->_type, AtomicInterface::class, true)) {
            return (function &() {
                foreach ($this->_container as $k => $v) {
                    $v = $v->get();
                    yield $k => $v;
                    $this->_container[$k]->set($v);
                }
            })();
        }
        else {
            return (function &() {
                foreach ($this->_container as $k => $v) {
                    yield $k => $v;
                    $this->offsetSet($k, $v);
                }
            })();
        }
    }

    /**
     * @return void
     */
    public function setConversionOptionsToNested(): void
    {
        if (is_a($this->_type, TypedAbstract::class, true)) {
            foreach ($this->_container as $v) {
                $v->conversionOptions->set($this->conversionOptions->get());
                $v->setConversionOptionsToNested();
            }
        }
    }

    /**
     * Returns an array with all members checked for a "toArray" method so
     * that any member of type "Typed" will also be returned.
     * Use $arr[$member] to access individual names.
     *
     * @return array
     */
    public function toArray(): array
    {
        $omitEmpty       = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);
        $dateToString    = $this->conversionOptions->isset(ConversionOptions::DATE_TO_STRING);
        $objectsToString = $this->conversionOptions->isset(ConversionOptions::ALL_OBJECTS_TO_STRING);

        $output = [];
        if (is_a($this->_type, AtomicInterface::class, true)) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v->get();
            }
        }
        elseif (method_exists($this->_type, 'toArray')) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v->toArray();
            }
        }
        elseif (
            ($dateToString && is_a($this->_type, DateTime::class, true)) ||
            ($objectsToString && method_exists($this->_type, '__toString'))
        ) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v->__toString();
            }
        }
        elseif ($this->_type === '') {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v;
            }
        }
        elseif (!self::_isAssignable($this->_type)) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = (array)$v;
            }
        }
        else {
            //  Is this needed?
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v;
            }
        }

        if ($omitEmpty) {
            self::_removeEmpties($output);
        }

        return $output;
    }

    /**
     * JsonSerializable::jsonSerialize()
     *
     * Called automatically when object is passed to json_encode().
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        $keepJsonExpr = $this->conversionOptions->isset(ConversionOptions::KEEP_JSON_EXPR);
        $omitEmpty    = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);

        $output = [];
        if (method_exists($this->_type, 'jsonSerialize')) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v->jsonSerialize();
            }
        }
        elseif ($keepJsonExpr && $this->_type === '\\Laminas\\Json\\Expr') {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v;
            }
        }
        else {
            return $this->toArray();
        }

        if ($omitEmpty) {
            self::_removeEmpties($output);
        }

        return $output;
    }

    /**
     * Removes empty items from referenced array.
     *
     * @param array $arr
     *
     * @return void
     */
    protected static function _removeEmpties(array &$arr): void
    {
        //	Is this an indexed array?
        $indexed = array_is_list($arr);

        //	Remove empty items.
        foreach ($arr as $k => $v) {
            if (empty($v)) {
                unset($arr[$k]);
            }
        }

        //	If it's an indexed array (not an associative array) then fix the indexes.
        if ($indexed) {
            $arr = array_values($arr);
        }
    }

    /**
     * Merge input with clone of this and return new TypedArray.
     *
     * Similar to the function array_merge().
     *
     * @param  $in
     *
     * @return TypedArray
     */
    public function merge($in): TypedArray
    {
        $this->_massageInput($in);

        $ret = clone $this;

        if (array_is_list($in)) {
            foreach ($in as $v) {
                $ret[] = $v;
            }
        }
        else {
            foreach ($in as $k => $v) {
                $ret[$k] = $v;
            }
        }

        return $ret;
    }

    /**
     * Required by the ArrayAccess interface.
     *
     * @param string|int $offset
     *
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return $offset !== '' && $offset !== null && array_key_exists($offset, $this->_container);
    }

    /**
     * Required by the ArrayAccess interface.
     * Returns value at offset.
     *
     * This method cannot return a reference since a reference might contain a scalar
     * wrapper. We want the scalar, not the wrapper. Objects are always passed by reference.
     *
     * Using a reference is handled in getIterator.
     *
     * http://stackoverflow.com/questions/20053269/indirect-modification-of-overloaded-element-of-splfixedarray-has-no-effect
     *
     * @param string|int $offset
     *
     * @return mixed
     */
    public function offsetGet(mixed $offset): mixed
    {
        if (!$this->offsetExists($offset)) {
            //	Be sure offset exists before accessing it.
            $this->offsetSet($offset, null);

            //	Returns new offset created by ::offsetSet().
            if (null === $offset) {
                end($this->_container);
                $offset = key($this->_container);
            }
        }

        if ($this->_type instanceof AtomicInterface) {
            return $this->_container[$offset]->get();
        }

        return $this->_container[$offset];
    }

    /**
     * Required by the ArrayAccess interface
     * Coerces input values to be the required type.
     *
     * There are 5 basic conditions for $this->_type:
     * * $this->_type is null (accept any type and value, like a standard array);
     * * $this->_type is a scalar wrapper [bool, int, float, string];
     * * $this->_type is an array (check if input value is an object and has toArray);
     * * $this->_type is an object of type TypedAbstract (call assign());
     * * $this->_type is any other object.
     *
     * There are 3 conditions involving $offset:
     * * $offset is null;
     * * $offset is set and does not exist (null);
     * * $offset is set and exists;
     *
     * There are 4 conditions for handling $value:
     * * $value is null (assign current scalar values with null, reset non-scalars);
     * * $value is a scalar (cast);
     * * $value is a an array (check for toArray, or cast);
     * * $value is a an object (clone if the same as _type, otherwise new _type(value) );
     *
     * @param string|int $offset
     * @param mixed      $value
     */
    public function offsetSet($offset, $value): void
    {
        if (self::_setBasicTypeAndConfirm($value, $this->_type)) {
            $this->_container[$offset] = $value;
            return;
        }

        if ($this->_type instanceof AtomicInterface) {
            if (!isset($this->_container[$offset])) {
                $this->_container[$offset] = new $this->_type($value);
            }
            else {
                $this->_container[$offset]->set($value);
            }
            return;
        }

        if ($this->_type instanceof TypedAbstract) {
            if (!isset($this->_container[$offset])) {
                $this->_container[$offset] = new $this->_type($value);
            }
            else {
                $this->_container[$offset]->assign($value);
            }
            return;
        }

        // if the object types match then
        if (is_object($value) && get_class($value) === $this->_type) {
            $this->_offsetSet($offset, $value);
            return;
        }

        $this->_offsetSet($offset, new $this->_type($value));
    }

    /**
     * @param $offset
     * @param $value
     *
     * @return void
     */
    private function _offsetSet($offset, $value)
    {
        if (null === $offset) {
            $this->_container[] = $value;
        }
        else {
            $this->_container[$offset] = $value;
        }
    }

    /**
     * Required by the ArrayAccess interface.
     *
     * @param string|int $offset
     */
    public function offsetUnset($offset): void
    {
        unset($this->_container[$offset]);
    }

    /**
     * Make sure object is deep copied.
     */
    public function __clone()
    {
        if ($this->_type === '') {
            foreach ($this->_container as &$v) {
                // container accepts anything so test each value
                if (is_object($v)) {
                    $v = clone $v;
                }
            }
        }
        elseif (!self::_isAssignable($this->_type)) {
            // If not assignable then these must all already be objects.
            foreach ($this->_container as &$v) {
                $v = clone $v;
            }
        }
    }

    /**
     * Returns array keys.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->_container);
    }

    /**
     * Apply new names from input array values.
     *
     * @param array $keys
     *
     * @return TypedArray
     * @throws LengthException
     */
    public function combine(array $keys): self
    {
        if (count($keys) !== count($this->_container)) {
            throw new LengthException('array counts do not match');
        }

        $this->_container = array_combine($keys, $this->_container);

        return $this;
    }

    /**
     * Behave like array_shift.
     *
     * @return mixed
     */
    public function shift()
    {
        return array_shift($this->_container);
    }

    /**
     * @return array
     */
    public function values(): array
    {
        return array_values($this->_container);
    }
}
