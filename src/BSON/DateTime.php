<?php


namespace Diskerror\Typed\BSON;

use DateTimeZone;
use MongoDB\BSON\Serializable;
use MongoDB\BSON\Unserializable;
use MongoDB\BSON\UTCDateTime;

class DateTime extends \Diskerror\Typed\DateTime implements Serializable, Unserializable
{
    use DateTrait;

    private static function _toArray(self $d)
    {
        return ['$date' => ['$numberLong' => (string)(new UTCDateTime($d))]];
    }

    /**
     * @return array
     */
    public function bsonSerialize(): array
    {
        $tzName = $this->getTimezone()->getName();
        if ($tzName === 'UTC' || substr($tzName, -5) === '00:00' || substr($tzName, -4) === '0000') {
            return self::_toArray($this);
        }
        $d = clone $this;
        $d->setTimezone(new DateTimeZone('UTC'));
        return self::_toArray($d);
    }

    /**
     * @param array $data
     * @return : void
     */
    public function bsonUnserialize(array $data): void
    {
        $tz = $this->getTimezone();
        $this->setTimezone(new DateTimeZone('UTC'));
        $this->setTime(0, 0, 0, $data['milliseconds'] * 1000);
        $this->setTimezone($tz);
    }
}
