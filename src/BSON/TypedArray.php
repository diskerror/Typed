<?php
/**
 * Create an array where members must be the same type.
 *
 * @name        \Diskerror\TypedBSON\TypedArray
 * @copyright   Copyright (c) 2012 Reid Woodbury Jr
 * @license     http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\BSON;

use DateTimeInterface;
use Diskerror\Typed\ConversionOptions;
use InvalidArgumentException;
use MongoDB\BSON\{Document, ObjectId, PackedArray, Serializable, Unserializable, UTCDateTime};
use stdClass;

/**
 * Provides support for an array's elements to all have the same type.
 * If type is defined as null then any element can have any type but
 *      deep copying of objects is always available.
 */
class TypedArray extends \Diskerror\Typed\TypedArray implements Serializable, Unserializable
{
    public function __construct(mixed $param1 = null, mixed $param2 = null)
    {
        if (get_called_class() === self::class) {
            $this->_type = is_string($param1) ? $param1 : '';

            $param1 = $param2;
            $param2 = null;
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
        parent::__construct($param1, $param2);
    }

    /**
     * Called automatically by MongoDB.
     *
     * @return array|Document|PackedArray|stdClass
     */
    public function bsonSerialize(): array|Document|PackedArray|stdClass
    {
        $dateToString = $this->conversionOptions->isset(ConversionOptions::DATE_TO_STRING);
        $omitEmpty    = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);
        $castObjectId = $this->conversionOptions->isset(ConversionOptions::CAST_ID_TO_OBJECTID);

        $output = [];
        if (method_exists($this->_type, 'bsonSerialize')) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = $v->bsonSerialize();
            }
        }
        elseif (!$dateToString && is_a($this->_type, DateTimeInterface::class, true)) {
            foreach ($this->_container as $k => $v) {
                $output[$k] = (array)new UTCDateTime($v);
            }
        }
        else {
            $output = $this->toArray();
        }

        if ($omitEmpty) {
            self::_removeEmpties($output);
        }

        /**
         * Cast "_id" string or number into a MongoDB\BSON\ObjectId.
         */
        if ($castObjectId && array_key_exists('_id', $output) && is_scalar($output['_id'])) {
            $output['_id'] = new ObjectId(empty($output['_id']) ? null : (string)$output['_id']);
        }

        return $output;
    }

    /**
     *
     * @param array $data
     */
    public function bsonUnserialize(array $data): void
    {
        $this->clear();
        $this->assign($data);
    }
}
