<?php

class ArrayTest extends PHPUnit\Framework\TestCase
{
	public function testWalk()
	{
		$walk = new Diskerror\Typed\TypedArray('string', ['1', 2, '3', 'z', 5]);
//		jsonPrint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array1.json',
			json_encode($walk->toArray()),
			'Creation of simple array of strings.'
		);

		foreach ($walk as &$w) {
			$w *= 2;
		}
//		jsonPrint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array2.json',
			json_encode($walk->toArray()),
			'Multiple strings by number.'
		);

		foreach ($walk as &$w) {
			$w = [$w, "elephant"];
		}
//		jsonPrint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array3.json',
			json_encode($walk->toArray()),
			'Attempt to convert strings to arrays.'
		);
	}
}
