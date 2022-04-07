<?php

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\TypedArray;

class ArrayTest extends PHPUnit\Framework\TestCase
{
	/**
	 * @expectedException	PHPUnit\Framework\Error\Warning
	 */
	public function testWalk()
	{
		$walk = new TypedArray(TString::class, ['1', 2, '3', 'z', 5]);
//		jsonPrint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array1.json',
			json_encode($walk->toArray()),
			'Creation of simple array of strings.'
		);

		foreach ($walk as &$w) {
			$w = ((integer) $w) * 2;
		}
//		jsonPrint($walk->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/array2.json',
			json_encode($walk->toArray()),
			'Multiply strings by number.'
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
