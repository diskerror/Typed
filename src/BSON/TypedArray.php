<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        \Diskerror\TypedBSON\TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\BSON;

use Diskerror\Typed\ConversionOptions;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Persistable;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends \Diskerror\Typed\TypedArray implements Persistable
{
    use TypedTrait;

    /**
     * Constructor.
     *
     * If this class is instantiated directly, ie. "$a = new TypedArray('integer', [1, 2, 3]);",
     * then $param1 must be the data type as a string, and then $param2 can be the initial data.
     *
     * If a derived class is instantiated then the data type must be contained in
     * the class, ie. "protected $_type = 'integer';", and $param1 can be the initial data.
     *
     * @param mixed             $param1 OPTIONAL ""
     * @param array|object|null $param2 OPTIONAL null
     */
    public function __construct($param1 = '', $param2 = null)
    {
        $this->_initToArrayOptions();

        if (get_called_class() === self::class) {
            $this->_type = (string)$param1;
            parent::__construct($param2);
        }
        else {
            parent::__construct($param1, $param2);
        }
    }

    /**
     * Called automatically by MongoDB.
     *
     * @return array
     */
    public function bsonSerialize(): array
    {
        $output = [];

        if (is_a($this->_type, \Diskerror\Typed\DateTime::class, true)) {
            if ($this->toBsonOptions->has(ConversionOptions::DATE_TO_STRING)) {
                foreach ($this->_container as $k => $v) {
                    $output[$k] = $v->jsonSerialize();
                }
            }
            else {
                foreach ($this->_container as $k => $v) {
                    $output[$k] = $v;
                }
            }

            if ($this->toBsonOptions->has(ConversionOptions::OMIT_EMPTY)) {
                self::_removeEmpty($output);
            }
        }
        else {
            $output = $this->toArray();
        }

        /**
         * Cast "_id" string or number into a MongoDB\BSON\ObjectId.
         */
        if (
            $this->toBsonOptions->has(ConversionOptions::CAST_ID_TO_OBJECTID) &&
            array_key_exists('_id', $output) && is_scalar($output['_id'])
        ) {
            if ($output['_id'] == 0) {
                $output['_id'] = new ObjectId();
            }
            else {
                $output['_id'] = new ObjectId((string)$output['_id']);
            }
        }

        return $output;
    }

    /**
     *
     * @param array $data
     */
    public function bsonUnserialize(array $data)
    {
        $this->_initToArrayOptions();
        $this->replace($data);
    }
}
