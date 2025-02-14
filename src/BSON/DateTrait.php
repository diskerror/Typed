<?php

namespace Diskerror\Typed\BSON;

use DateTimeZone;
use MongoDB\BSON\UTCDateTimeInterface;

/**
 *	Methods to be added to both DateTime and Date.
 */
trait DateTrait
{
    public function __construct(mixed $time = 'now', $timezone = null)
    {
        if (is_object($time) && $time instanceof UTCDateTimeInterface) {
            $time     = $time->toDateTime();
            $timezone = new DateTimeZone('UTC');
        }
        parent::__construct($time, $timezone);
    }
}
