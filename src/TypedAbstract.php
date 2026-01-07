<?php
/**
 * Methods for maintaining variable type.
 *
 * @name           TypedAbstract
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

use Countable;
use InvalidArgumentException;
use IteratorAggregate;
use JsonSerializable;

/**
 * Class TypedAbstract
 * Provides common interface and core methods for TypedClass and TypedArray.
 *
 * @package Diskerror\Typed
 */
abstract class TypedAbstract implements Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Holds options for reducing this object to a PHP array or for serializations.
     *
     * @var ConversionOptions
     */
    public ConversionOptions $conversionOptions;

    /**
     * Assignable types can be simply assigned, as in $a = $b.
     * The remainders would be objects which often need to be cloned.
     *
     * @param string $type
     *
     * @return bool
     */
    final protected static function _isAssignable(string $type): bool
    {
        if (self::_isScalar($type)) {
            return true;
        }

        switch ($type) {
            case 'array':
            case 'resource':
            case 'callable':
                return true;
        }

        return false;
    }

    /**
     * Similar to the function is_scalar() but takes the type name as a string including 'null'.
     *
     * @param string $type
     *
     * @return bool
     */
    final protected static function _isScalar(string $type): bool
    {
        switch ($type) {
            case 'NULL':
            case 'null':
            case 'bool':
            case 'boolean':
            case 'int':
            case 'integer':
            case 'float':
            case 'double':
            case 'string':
                return true;
        }

        return false;
    }

    /**
     * The function settype() may return different values than type casting.
     *
     * @param        $val
     * @param string $type
     *
     * @return bool
     */
    final protected static function _setBasicTypeAndConfirm(&$val, string $type): bool
    {
        $valType = gettype($val);

        switch ($type) {
            case '':
            case 'NULL':
                break;

            case 'resource':
            case 'callable':
                if ($valType !== $type) {
                    $val = true;
                }
                break;

            case 'string':
                switch ($valType) {
                    case 'object':
                        if (method_exists($val, '__toString')) {
                            $val = (string)$val;
                            break;
                        }
                    case 'array':
                        $val       = json_encode($val);
                        $lastError = json_last_error();
                        if ($lastError !== JSON_ERROR_NONE) {
                            throw new InvalidArgumentException(
                                'problem converting input data to JSON: ' . json_last_error_msg(),
                                $lastError
                            );
                        }
                        break;

                    default:
                        $val = (string)$val;
                }
                break;

            case 'int':
            case 'integer':
                switch ($valType) {
                    case 'object':
                        if (is_a($val, AtomicInterface::class, true)) {
                            $val = $val->get();
                            break;
                        }
                    //	else fall through
                    case 'array':
                        $val = count($val);
                        break;

                    default:
                        $val = (int)$val;
                }
                break;

            case 'float':
            case 'double':
                $val = (double)$val;
                break;

            case 'bool':
            case 'boolean':
                switch ($valType) {
                    case 'array':
                        $val = !empty($val);
                        break;

                    case 'object':
                        if (is_a($val, AtomicInterface::class, true)) {
                            $val = (bool)$val->get();
                        }
                        else {
                            $val = !empty((array)$val);
                        }
                        break;

                    default:
                        $val = (bool)$val;
                }
                break;

            case 'array':
                $val = (array)$val;
                break;

            default:
                return false;
        }

        return true;
    }

    /**
     * Assign. Replace values by name.
     *
     * Assign values from input object. Only named input items are copied.
     * Missing keys are left untouched. Deep copy is performed.
     *
     * @param mixed $in
     */
    abstract public function assign($in): void;

    /**
     * Clear all values.
     *
     * All values are set to zero or empty.
     *
     * @return void
     */
    abstract public function clear(): void;

    /**
     * Merge $this struct with $in struct and return new structure. Input
     * values will assign cloned values where keys match.
     *
     * @param mixed $in
     *
     * @return self
     */
    abstract public function merge($in): self;

    /**
     * @return void
     */
    abstract public function setConversionOptionsToNested(): void;

    /**
     * Returns an array with all public, protected, and private properties in
     * object that DO NOT begin with an underscore, except "_id". This allows
     * protected or private properties to be treated as if they were public.
     * This supports the convention that protected and private property names
     * begin with an underscore (_).
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * JsonSerializable::jsonSerialize()
     *
     * Called automatically when object is passed to json_encode().
     *
     * @return array
     */
    abstract public function jsonSerialize(): array;

    /**
     * Check if the input data is good or needs to be massaged.
     *
     * @param $in
     *
     * @throws InvalidArgumentException
     */
    protected function _massageInput(&$in): void
    {
        switch (gettype($in)) {
            case 'array':
            case 'object':
                // Leave these as is.
                break;

            case 'NULL':
                $in = [];
                break;

            case 'string':
                if ('' === $in) {
                    $in = [];
                }
                else {
                    $in        = json_decode($in, JSON_OBJECT_AS_ARRAY);
                    $lastError = json_last_error();
                    if ($lastError !== JSON_ERROR_NONE) {
                        throw new InvalidArgumentException(
                            'invalid input type (string); tried as JSON: ' . json_last_error_msg(),
                            $lastError
                        );
                    }
                }
                break;

            case 'boolean':
                // A 'false' is returned by MySQL:PDO for "no results".
                if (true !== $in) {
                    /** Change false to empty array. */
                    $in = [];
                }
            //	A boolean 'true' falls through.

            default:
                throw new InvalidArgumentException(get_called_class() . ': bad input type ' . gettype($in) . ', value: "' . $in . '"');
        }
    }
}
