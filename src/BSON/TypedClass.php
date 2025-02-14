<?php

/**
 * Provides support for class members/properties maintain their initial types.
 *
 * @name           \Diskerror\Typed\BSON\TypedClass
 * @copyright      Copyright (c) 2012 Reid Woodbury Jr
 * @license        http://www.apache.org/licenses/LICENSE-2.0.html Apache License, Version 2.0
 */

namespace Diskerror\Typed\BSON;

use Diskerror\Typed\ConversionOptions;
use MongoDB\BSON\{Document, ObjectId, PackedArray, Serializable, Unserializable};
use stdClass;

class TypedClass extends \Diskerror\Typed\TypedClass implements Serializable, Unserializable
{
    /**
     * Called automatically by MongoDB.
     *
     * @return array|Document|PackedArray|stdClass
     */
    public function bsonSerialize(): array|Document|PackedArray|stdClass
    {
        $omitEmpty    = $this->conversionOptions->isset(ConversionOptions::OMIT_EMPTY);
        $castObjectId = $this->conversionOptions->isset(ConversionOptions::CAST_ID_TO_OBJECTID);

        $arr = $this->toArray();
        foreach ($this->_meta as $k => $meta) {
            if ($meta->isObject && method_exists($this->$k, 'bsonSerialize')) {
                $arr[$k] = $this->$k->bsonSerialize();
            }

            //	Testing for empty must happen after nested objects have been reduced.
            if ($omitEmpty && isset($arr[$k]) && empty($arr[$k])) {
                unset($arr[$k]);
            }
        }

        /**
         * Cast "_id" string or number into a MongoDB\BSON\ObjectId.
         */
        if ($castObjectId && array_key_exists('_id', $arr) && is_scalar($arr['_id'])) {
            $arr['_id'] = new ObjectId(empty($arr['_id']) ? null : (string)$arr['_id']);
        }

        return $arr;
    }

    /**
     * Since zero, null, false, or empty strings can be omitted from the
     * serialized data stored in Mongo this method prevents non-empty defaults
     * from being written to the restored members.
     *
     * @param array $data
     */
    public function bsonUnserialize(array $data): void
    {
        $this->clear();
        $this->assign($data);
    }
}
