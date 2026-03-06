<?php

namespace Diskerror\Typed;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class AttributeMap
{
	public function __construct(
		public string $name
	) {}
}
