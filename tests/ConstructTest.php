<?php

class ConstructTest extends PHPUnit\Framework\TestCase
{
	public function testEmptyConstructor()
	{
		$simp = new SimpleTyped();

		$this->assertTrue(is_bool($simp->myBool));
		$this->assertTrue(is_int($simp->myInt));
		$this->assertTrue(is_double($simp->myFloat));
		$this->assertTrue(is_string($simp->myString));
		$this->assertTrue(is_object($simp->myArray));
		$this->assertTrue(is_object($simp->myObj));

// 		echo json_encode($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp1-15.json',
			json_encode($simp)
		);


		$simp->myBool    = 76;
		$simp->myFloat   = 4;
		$simp->myObj->nv = 'new variable';
		$simp->myArray   = null;

		$this->assertTrue(is_bool($simp->myBool));
		$this->assertTrue(is_int($simp->myInt));
		$this->assertTrue(is_double($simp->myFloat));
		$this->assertTrue(is_string($simp->myString));
		$this->assertTrue(is_object($simp->myArray));
		$this->assertTrue(is_object($simp->myObj));

		// echo jsonEncode($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp1-32.json',
			json_encode($simp)
		);
	}

	public function testIndexedArrayConstruct()
	{
		$input = [false, 77, .5, 'simpppp2'];
		$simp  = new SimpleTyped($input);

		$this->assertTrue(is_bool($simp->myBool));
		$this->assertTrue(is_int($simp->myInt));
		$this->assertTrue(is_double($simp->myFloat));
		$this->assertTrue(is_string($simp->myString));
		$this->assertTrue(is_object($simp->myArray));
		$this->assertTrue(is_object($simp->myObj));

		// echo jsonEncode($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp2-48.json',
			json_encode($simp)
		);
	}

	public function testAssocArrayConstruct()
	{
		$arr  = ['myString' => 234445, 'myInt' => 3.14, 'myNotExist' => 'to be ignored'];
		$simp = new SimpleTyped($arr);

		$this->assertTrue(is_bool($simp->myBool));
		$this->assertTrue(is_int($simp->myInt));
		$this->assertTrue(is_double($simp->myFloat));
		$this->assertTrue(is_string($simp->myString));
		$this->assertTrue(is_object($simp->myArray));
		$this->assertTrue(is_object($simp->myObj));

		// echo jsonEncode($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp3-65.json',
			json_encode($simp)
		);
	}

	public function testWithClassConstruct()
	{
		$obj                  = new stdClass();
		$obj->myNothing       = 'ignored';
		$obj->myDouble        = '314 dropped';
		$obj->myString        = 'very complicated';
		$obj->myArray         = ['a', 'b', 'c'];
		$obj->myObj           = new stdClass();
		$obj->myObj->anything = 'ha!';
		$obj->myObj->more     = 'much!';

		$simp = new SimpleTyped($obj);

		$this->assertTrue(is_bool($simp->myBool));
		$this->assertTrue(is_int($simp->myInt));
		$this->assertTrue(is_double($simp->myFloat));
		$this->assertTrue(is_string($simp->myString));
		$this->assertTrue(is_object($simp->myArray));
		$this->assertTrue(is_object($simp->myObj));

		$simp->myDouble = 3.14;
		$simp->myInt    = null;
		$simp->myInt    = 2.54;

		// echo jsonEncode($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp4-93.json',
			json_encode($simp)
		);
	}

}
