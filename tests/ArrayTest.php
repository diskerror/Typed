<?php

class ArrayTest extends PHPUnit\Framework\TestCase
{
	public function testWalk()
	{
		$walk = new Diskerror\Typed\TypedArray(['1', '2', '3', '4', '5'], 'string');
		jsonPrint($walk);

		foreach ($walk as &$w) {
			$w *= 2;
		}
		jsonPrint($walk);

		foreach ($walk as &$w) {
			$w = 8;
		}
		jsonPrint($walk);
	}
}
