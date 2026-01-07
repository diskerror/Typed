<?php

namespace Diskerror\Typed\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Map
{
    public function __construct(
        public string $name
    ) {
    }
}
