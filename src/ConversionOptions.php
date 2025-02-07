<?php
/**
 * Manages the bit-wise options for converting a typed object into an associative array.
 *
 * @name           ConversionOptions
 * @copyright      Copyright (c) 2017 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed;

/**
 * Class ConversionOptions
 *
 * @package Diskerror\Typed
 */
class ConversionOptions extends BitWise
{
    /**
     * None.
     */
    public const NONE = 0;

    /**
     * Omit empty properties from the array that is output.
     */
    public const OMIT_EMPTY = 1;

    /**
     * Omit resources from the array that is output.
     * This is only meaningful for the toArray() method.
     * The serialization methods always omit resources.
     */
    public const OMIT_RESOURCE = 2;

    /**
     * Convert date objects to strings.
     */
    public const DATE_TO_STRING = 4;

    /**
     * Convert all other objects to string, if possible.
     */
    public const ALL_OBJECTS_TO_STRING = 8;

    /**
     * For Zend JSON encoding to JSON, these objects contain strings that should _not_ be quoted.
     */
    public const KEEP_JSON_EXPR = 16;

    /**
     * Cast member with the name "_id" into MongoDB\BSON\ObjectId.
     */
    public const CAST_ID_TO_OBJECTID = 32;

    /**
     * Constructor with default values.
     *
     * @param int $options
     */
    public function __construct(int $options = self::NONE)
    {
        parent::__construct($options);
    }
}
