<?php

namespace Diskerror\Typed;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AtMap
{
    public function __construct(
        public string $name
    ) {
    }
}
