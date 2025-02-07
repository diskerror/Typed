<?php

namespace Diskerror\Typed\BSON;

use MongoDB\BSON\Persistable;

class Date extends \Diskerror\Typed\Date implements Persistable
{
    use DateTrait;

    public function bsonSerialize(): array
    {
        return [$this->format('Y-m-d')];
    }

    public function bsonUnserialize(array $data): void
    {
        [$year, $month, $day] = explode('-', $data[0]);
        $this->setDate($year, $month, $day);
    }
}
