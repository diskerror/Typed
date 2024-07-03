<?php /** @noinspection Annotator */

use Diskerror\Typed\DateTime as MyDateTime;
use Diskerror\Typed\TypedArray;
use PHPUnit\Framework\TestCase;
use TestClasses\SimpleTyped;

class AssignTest extends TestCase
{
	public function testAssignBool()
	{
		$t = new SimpleTyped();

		$this->assertTrue($t->myBool);

		$t->myBool = false;
		$this->assertFalse($t->myBool);
		$t->myBool = true;
		$this->assertTrue($t->myBool);

		$t->myBool = '';
		$this->assertFalse($t->myBool);
		$t->myBool = 'some text';
		$this->assertTrue($t->myBool);

		$t->myBool = 77.3;
		$this->assertTrue($t->myBool);
		$t->myBool = 0;
		$this->assertFalse($t->myBool);

		$t->myBool = null;
		$this->assertNull($t->myBool);

		$t->myBool = [];
		$this->assertFalse($t->myBool);
		$t->myBool = ['a'];
		$this->assertTrue($t->myBool);

		$c         = new stdClass();
		$t->myBool = $c;
		$this->assertFalse($t->myBool);
		$c->aMember = 'string data';
		$t->myBool  = $c;
		$this->assertTrue($t->myBool);

		unset($t->myBool);
		$this->assertNull($t->myBool);
	}

	public function testAssignInt()
	{
		$t = new SimpleTyped();

		////////////////////////////////////////////////////////////////////////
		//	Integer.
		$this->assertEquals(0, $t->myInt);

		$t->myInt = 77;
		$this->assertEquals(77, $t->myInt);

		$t->myInt = 3.1415;
		$this->assertEquals(3, $t->myInt);

		$t->myInt = 'meaningless string';
		$this->assertEquals(0, $t->myInt);
		$this->assertTrue(is_int($t->myInt));

		$t->myInt = '-2.4 marginal words';
		$this->assertEquals(-2, $t->myInt);
		$this->assertTrue(is_int($t->myInt));

		$t->myInt = true;
		$this->assertEquals(1, $t->myInt);
		$this->assertTrue(is_int($t->myInt));

		$t->myInt = false;
		$this->assertEquals(0, $t->myInt);
		$this->assertTrue(is_int($t->myInt));

		$t->myInt = null;
		$this->assertEquals(0, $t->myInt);

		$t->myInt = [];
		$this->assertEquals(0, $t->myInt);
		$t->myInt = ['a', 'b'];
		$this->assertEquals(1, $t->myInt);

		$c        = new stdClass();
		$t->myInt = $c;
		$this->assertEquals(0, $t->myInt);
		$c->aMember = 'string data';
		$t->myInt   = $c;
		$this->assertEquals(1, $t->myInt);

		unset($t->myInt);
		$this->assertEquals(0, $t->myInt);
	}

	public function testAssignFloat()
	{
		$t = new SimpleTyped();

		////////////////////////////////////////////////////////////////////////
		//	Float.
		$this->assertEquals(3.14, $t->myFloat);

		$t->myFloat = 77;
		$this->assertTrue($t->myFloat === 77.0);

		$t->myFloat = 3.1415;
		$this->assertEquals(3.1415, $t->myFloat);

		$t->myFloat = 'meaningless string';
		$this->assertTrue($t->myFloat === 0.0);

		$t->myFloat = '-2.4 marginal words';
		$this->assertEquals(-2.4, $t->myFloat);

		$t->myFloat = true;
		$this->assertTrue($t->myFloat === 1.0);

		$t->myFloat = false;
		$this->assertTrue($t->myFloat === 0.0);

		$t->myFloat = null;
		$this->assertEquals(0, $t->myFloat);

		$t->myFloat = [];
		$this->assertTrue($t->myFloat === 0.0);
		$t->myFloat = ['a', 'b'];
		$this->assertTrue($t->myFloat === 1.0);

		$c          = new stdClass();
		$t->myFloat = $c;
		$this->assertTrue($t->myFloat === 0.0);
		$c->aMember = 'string data';
		$t->myFloat = $c;
		$this->assertTrue($t->myFloat === 1.0);

		unset($t->myFloat);
		$this->assertEquals(0, $t->myFloat);
	}

	public function testAssignString()
	{
		$t = new SimpleTyped();

		////////////////////////////////////////////////////////////////////////
		//	String.
		$this->assertEquals('', $t->myString);

		$t->myString = 77;
		$this->assertTrue($t->myString === '77');

		$t->myString = 3.14150;
		$this->assertTrue($t->myString === '3.1415');

		$t->myString = 'meaningful string';
		$this->assertEquals('meaningful string', $t->myString);

		$t->myString = true;
		$this->assertTrue($t->myString === '1');
		$t->myString = false;
		$this->assertEquals('', $t->myString);

		$t->myString = null;
		$this->assertEquals('', $t->myString);

		$t->myString = ['a', 'b'];
		$this->assertEquals('["a","b"]', $t->myString);

		$c           = new stdClass();
		$c->aMember  = 'string data';
		$t->myString = $c;
		$this->assertEquals('{"aMember":"string data"}', $t->myString);

		unset($t->myString);
		$this->assertEquals('', $t->myString);
	}

	public function testAssignArray()
	{
		$t = new SimpleTyped();

		////////////////////////////////////////////////////////////////////////
		//	Array.
		$this->assertIsArray($t->myArray->toArray());

		$t->myArray = [77];
		$this->assertEquals([77], $t->myArray->toArray());

		$t->myArray = [3.14150];
		$this->assertEquals([3.1415], $t->myArray->toArray());

		$t->myArray = [true];
		$this->assertEquals([true], $t->myArray->toArray());

		$t->myArray = null;
		$this->assertEquals([], $t->myArray->toArray());
		$this->assertTrue(is_object($t->myArray));

		$t->myArray = ['a', 'b'];
		$this->assertEquals(new TypedArray('', ['a', 'b']), $t->myArray);

		$c          = new stdClass();
		$c->aMember = 'string data';
		$t->myArray = $c;
		$this->assertEquals(new TypedArray('', ['aMember' => 'string data', 'a', 'b']), $t->myArray);

		unset($t->myArray);
		$this->assertEquals(new TypedArray(), $t->myArray);
	}

//	public function testAssignObject()
//	{
//		$t = new SimpleTyped();
//
//		////////////////////////////////////////////////////////////////////////
//		//	Generic object.
//		$this->assertEquals(new stdClass(), $t->myObj);
//
//		$t->myObj = 77;
//		$this->assertEquals(new stdClass(), $t->myObj);
//
//		$t->myObj = null;
//		$this->assertEquals(new stdClass(), $t->myObj);
//
//		$t->myObj    = ['first' => 'a', 'second' => 'b'];
//		$obj         = new stdClass();
//		$obj->first  = 'a';
//		$obj->second = 'b';
//		$this->assertEquals($obj, $t->myObj);
//
//		$c          = new stdClass();
//		$c->aMember = 'string data';
//		$t->myObj   = $c;
//		$this->assertTrue($t->myObj === $c);
//
//		unset($t->myObj);
//		$this->assertEquals(new stdClass(), $t->myObj);
//	}

	public function testAssignDate()
	{
		$t = new SimpleTyped();

		////////////////////////////////////////////////////////////////////////
		//	Generic object.
		$this->assertInstanceOf(MyDateTime::class, $t->myDate);

		$this->assertEquals(new MyDateTime('2010-01-01T01:01:01.001000+0000'), $t->myDate);

		$this->assertEquals(new DateTime('2010-01-01T01:01:01.001000+0000'), $t->myDate);

		$this->assertNotSame(new MyDateTime('2010-01-01T01:01:01.001'), $t->myDate);

		$t->myDate = 77;
		$this->assertInstanceOf(MyDateTime::class, $t->myDate);
		$this->assertEquals(new MyDateTime(77), $t->myDate);

		$t->myDate = null;
		$this->assertEquals((new MyDateTime(null))->format(DATE_ATOM), $t->myDate->format(DATE_ATOM));

		$t->myDate = 'now';
		unset($t->myDate);
		$this->assertEquals((new MyDateTime(null))->format(DATE_ATOM), $t->myDate->format(DATE_ATOM));
	}

}
