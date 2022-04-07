<?php

namespace TestClasses\Config;

use Diskerror\Typed\TypedClass;

class Caches extends TypedClass
{
	protected Cache $index;
	protected Cache $tag_cloud;
	protected Cache $summary;
}
