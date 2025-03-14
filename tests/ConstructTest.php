<?php
/** @noinspection ALL */

use Diskerror\Typed\ConversionOptions;
use Diskerror\Typed\TypedArray;
use PHPUnit\Framework\TestCase;
use TestClasses\SimpleTyped;

class ConstructTest extends TestCase
{
	public function testEmptyConstructor()
	{
		$simp = new SimpleTyped();
        $simp->conversionOptions->unset();

		$this->assertIsBool($simp->myBool);
		$this->assertIsInt($simp->myInt);
		$this->assertIsFloat($simp->myFloat);
		$this->assertIsString($simp->myString);
		$this->assertInstanceOf(TypedArray::class, $simp->myArray);
		$this->assertInstanceOf('stdClass', $simp->myObj);

		/**
		 * The method "toArray" is used in the function "json_encode" so as to
		 * not invoke the method "jsonSerialize".
		 */
// 		vxprint($simp->toArray()); exit;
//		fprintf(STDERR, var_export($simp));exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp1-15.json',
			json_encode($simp)
		);


		$simp->myBool    = 76;
		$simp->myFloat   = 4;
		$simp->myObj->nv = 'new variable';
		$simp->myArray   = null;

		$this->assertIsBool($simp->myBool);
		$this->assertIsInt($simp->myInt);
		$this->assertIsFloat($simp->myFloat);
		$this->assertIsString($simp->myString);
		$this->assertInstanceOf(TypedArray::class, $simp->myArray);
		$this->assertInstanceOf('stdClass', $simp->myObj);

//		jsprint($simp);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp1-32.json',
			json_encode($simp)
		);
	}

	public function testIndexedArrayConstruct()
	{
		$input = [false, 77, .5, 'simpppp2'];
		$simp  = new SimpleTyped($input);
        $simp->conversionOptions->unset();

        $this->assertIsBool($simp->myBool);
		$this->assertIsInt($simp->myInt);
		$this->assertIsFloat($simp->myFloat);
		$this->assertIsString($simp->myString);
		$this->assertInstanceOf(TypedArray::class, $simp->myArray);
		$this->assertInstanceOf('stdClass', $simp->myObj);

//		tprint($simp); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp2-48.json',
			json_encode($simp)
		);
	}

	public function testAssocArrayConstruct()
	{
		$arr  = ['myString' => 234445, 'myInt' => 3.14, 'myNotExist' => 'to be ignored'];
		$simp = new SimpleTyped($arr);
        $simp->conversionOptions->set(ConversionOptions::OMIT_EMPTY);
        $simp->setConversionOptionsToNested();

        $this->assertIsBool($simp->myBool);
		$this->assertIsInt($simp->myInt);
		$this->assertIsFloat($simp->myFloat);
		$this->assertIsString($simp->myString);
		$this->assertInstanceOf(TypedArray::class, $simp->myArray);
		$this->assertInstanceOf('stdClass', $simp->myObj);

//		jsprint($simp->toArray()); exit;
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
        $simp->conversionOptions->set(ConversionOptions::OMIT_EMPTY);

        $this->assertIsBool($simp->myBool);
		$this->assertIsInt($simp->myInt);
		$this->assertIsFloat($simp->myFloat);
		$this->assertIsString($simp->myString);
		$this->assertInstanceOf(TypedArray::class, $simp->myArray);
		$this->assertInstanceOf('stdClass', $simp->myObj);

		$simp->myDouble = 3.14;
		$simp->myInt    = null;
		$simp->myInt    = 2.54;

//		tprint($simp->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/simp4-93.json',
			json_encode($simp)
		);

//        jsprint($simp->toArray());
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/results/simp4-93a.json',
            json_encode($simp->toArray())
        );
    }

}
