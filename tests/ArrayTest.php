<?php

use Diskerror\Typed\TypedArray;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
	public function testWalk()
	{
		$walk = new TypedArray('string', ['1', 2, '3', 'z', 5]);
        $walk->conversionOptions->unset();

//		tprint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array1.json',
			json_encode($walk->toArray()),
			'Creation of simple array of strings.'
		);

		foreach ($walk as &$w) {
			$w = (integer) $w * 2;
		}
//		tprint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array2.json',
			json_encode($walk),
			'Multiply strings by number.'
		);

		foreach ($walk as &$w) {
			$w = [$w, "elephant"];
		}
//		tprint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array3.json',
			json_encode($walk->toArray()),
			'Attempt to convert strings to arrays.'
		);
	}
}
