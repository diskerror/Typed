<?php

use Diskerror\Typed\TypedClass;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\AttributeMap;
use Diskerror\Typed\ConversionOptions;
use PHPUnit\Framework\TestCase;

// Test classes

class BasicTypedClass extends TypedClass
{
	protected int     $intVal      = 0;
	protected string  $strVal      = '';
	protected float   $floatVal    = 0.0;
	protected bool    $boolVal     = false;
	protected ?int    $nullableInt = null;
	protected ?string $nullableStr = null;
}

class TypedClassWithDefaults extends TypedClass
{
	protected int    $count = 10;
	protected string $name  = 'default';
}

class TypedClassWithNested extends TypedClass
{
	protected BasicTypedClass $child;
	protected TypedArray      $items;

	protected function _initProperties(): void
	{
		$this->items = new TypedArray('string');
	}
}

class TypedClassWithMap extends TypedClass
{
	protected array $_map = ['old_name' => 'newName'];

	#[AttributeMap('ext_id')]
	protected int    $id      = 0;
	protected string $newName = '';
}

class TypedClassWithPublic extends TypedClass
{
	public int    $pubInt  = 0;
	protected int $protInt = 0;
}

class TypedClassWithRelated extends TypedClass
{
	protected int $min = 0;
	protected int $max = 100;

	protected function _checkRelatedProperties(): void
	{
		if ($this->min > $this->max) {
			$this->min = $this->max;
		}
	}
}

class TypedClassTest extends TestCase
{
	// === Construction ===

	public function testEmptyConstruction()
	{
		$obj = new BasicTypedClass();
		$this->assertSame(0, $obj->intVal);
		$this->assertSame('', $obj->strVal);
		$this->assertSame(0.0, $obj->floatVal);
		$this->assertFalse($obj->boolVal);
		$this->assertNull($obj->nullableInt);
		$this->assertNull($obj->nullableStr);
	}

	public function testConstructWithAssocArray()
	{
		$obj = new BasicTypedClass(['intVal' => '42', 'strVal' => 123, 'boolVal' => 1]);
		$this->assertSame(42, $obj->intVal);
		$this->assertSame('123', $obj->strVal);
		$this->assertTrue($obj->boolVal);
	}

	public function testConstructWithIndexedArray()
	{
		$obj = new BasicTypedClass([42, 'hello', 3.14, true]);
		$this->assertSame(42, $obj->intVal);
		$this->assertSame('hello', $obj->strVal);
		$this->assertSame(3.14, $obj->floatVal);
		$this->assertTrue($obj->boolVal);
	}

	public function testConstructWithObject()
	{
		$src         = new stdClass();
		$src->intVal = '99';
		$src->strVal = 'from object';
		$obj         = new BasicTypedClass($src);
		$this->assertSame(99, $obj->intVal);
		$this->assertSame('from object', $obj->strVal);
	}

	public function testConstructWithJsonString()
	{
		$json = '{"intVal": 77, "strVal": "json"}';
		$obj  = new BasicTypedClass($json);
		$this->assertSame(77, $obj->intVal);
		$this->assertSame('json', $obj->strVal);
	}

	public function testConstructWithNull()
	{
		// null should be treated as empty
		$obj = new BasicTypedClass(null);
		$this->assertSame(0, $obj->intVal);
	}

	public function testConstructWithFalse()
	{
		// false (PDO no results) should be treated as empty
		$obj = new BasicTypedClass(false);
		$this->assertSame(0, $obj->intVal);
	}

	public function testConstructWithTrueThrows()
	{
		$this->expectException(InvalidArgumentException::class);
		new BasicTypedClass(true);
	}

	public function testConstructIgnoresExtraFields()
	{
		$obj = new BasicTypedClass(['intVal' => 5, 'nonExistent' => 'ignored']);
		$this->assertSame(5, $obj->intVal);
	}

	public function testConstructWithDefaults()
	{
		$obj = new TypedClassWithDefaults();
		$this->assertSame(10, $obj->count);
		$this->assertSame('default', $obj->name);
	}

	// === assign ===

	public function testAssignPartial()
	{
		$obj = new BasicTypedClass(['intVal' => 5, 'strVal' => 'original']);
		$obj->assign(['intVal' => 10]);
		$this->assertSame(10, $obj->intVal);
		$this->assertSame('original', $obj->strVal); // untouched
	}

	// === clear ===

	public function testClear()
	{
		$obj = new BasicTypedClass(['intVal' => 42, 'strVal' => 'hello']);
		$obj->clear();
		$this->assertSame(0, $obj->intVal);
		$this->assertSame('', $obj->strVal);
	}

	// === merge ===

	public function testMergeReturnsNewObject()
	{
		$obj    = new BasicTypedClass(['intVal' => 5, 'strVal' => 'orig']);
		$merged = $obj->merge(['strVal' => 'new']);
		$this->assertSame('new', $merged->strVal);
		$this->assertSame(5, $merged->intVal);
		// Original untouched
		$this->assertSame('orig', $obj->strVal);
	}

	// === Nested objects ===

	public function testNestedAssign()
	{
		$obj = new TypedClassWithNested([
											'child' => ['intVal' => 42, 'strVal' => 'nested'],
											'items' => ['a', 'b', 'c'],
										]);
		$this->assertSame(42, $obj->child->intVal);
		$this->assertSame('nested', $obj->child->strVal);
		$this->assertCount(3, $obj->items);
	}

	// === Property mapping ===

	public function testPropertyMapArray()
	{
		$obj = new TypedClassWithMap(['old_name' => 'mapped value']);
		$this->assertSame('mapped value', $obj->newName);
	}

	public function testPropertyMapAttribute()
	{
		$obj = new TypedClassWithMap(['ext_id' => 99]);
		$this->assertSame(99, $obj->id);
	}

	public function testMapViaGet()
	{
		$obj = new TypedClassWithMap(['old_name' => 'test']);
		$this->assertSame('test', $obj->old_name);
	}

	public function testMapViaSet()
	{
		$obj           = new TypedClassWithMap();
		$obj->old_name = 'via set';
		$this->assertSame('via set', $obj->newName);
	}

	// === Public vs protected ===

	public function testPublicPropertyNoCoercion()
	{
		// Public properties use PHP's built-in type checking, not Typed's coercion
		$obj          = new TypedClassWithPublic();
		$obj->protInt = '42'; // Protected: silently coerced
		$this->assertSame(42, $obj->protInt);

		// Public: assigned as-is (PHP handles type)
		$obj->pubInt = 99;
		$this->assertSame(99, $obj->pubInt);
	}

	// === Nullable handling ===

	public function testNullableSetNull()
	{
		$obj              = new BasicTypedClass();
		$obj->nullableInt = 42;
		$this->assertSame(42, $obj->nullableInt);
		$obj->nullableInt = null;
		$this->assertNull($obj->nullableInt);
	}

	public function testNullableEmptyStringBecomesNull()
	{
		$obj              = new BasicTypedClass();
		$obj->nullableInt = '';
		$this->assertNull($obj->nullableInt);
	}

	public function testNullableNullStringBecomesNull()
	{
		$obj              = new BasicTypedClass();
		$obj->nullableInt = 'null';
		$this->assertNull($obj->nullableInt);
	}

	public function testNullableNanStringBecomesNull()
	{
		$obj              = new BasicTypedClass();
		$obj->nullableInt = 'nan';
		$this->assertNull($obj->nullableInt);
	}

	public function testNonNullableSetNullBecomesZero()
	{
		$obj         = new BasicTypedClass(['intVal' => 42]);
		$obj->intVal = null;
		$this->assertSame(0, $obj->intVal);
	}

	// === _checkRelatedProperties ===

	public function testCheckRelatedProperties()
	{
		$obj = new TypedClassWithRelated(['min' => 50, 'max' => 10]);
		$this->assertSame(10, $obj->min); // min > max, so min = max
		$this->assertSame(10, $obj->max);
	}

	// === toArray ===

	public function testToArray()
	{
		$obj = new BasicTypedClass(['intVal' => 5, 'strVal' => 'test']);
		$arr = $obj->toArray();
		$this->assertSame(5, $arr['intVal']);
		$this->assertSame('test', $arr['strVal']);
		$this->assertArrayHasKey('nullableInt', $arr);
	}

	public function testToArrayOmitEmpty()
	{
		$obj = new BasicTypedClass(['intVal' => 5]);
		$obj->conversionOptions->set(ConversionOptions::OMIT_EMPTY);
		$arr = $obj->toArray();
		$this->assertArrayHasKey('intVal', $arr);
		$this->assertArrayNotHasKey('strVal', $arr);      // empty string omitted
		$this->assertArrayNotHasKey('nullableInt', $arr); // null omitted
	}

	// === jsonSerialize ===

	public function testJsonEncode()
	{
		$obj     = new BasicTypedClass(['intVal' => 42, 'strVal' => 'json']);
		$json    = json_encode($obj);
		$decoded = json_decode($json, true);
		$this->assertSame(42, $decoded['intVal']);
		$this->assertSame('json', $decoded['strVal']);
	}

	// === clone ===

	public function testCloneDeepCopy()
	{
		$obj                  = new TypedClassWithNested([
															 'child' => ['intVal' => 1],
														 ]);
		$clone                = clone $obj;
		$clone->child->intVal = 99;
		$this->assertSame(1, $obj->child->intVal);
	}

	// === count / getPublicNames ===

	public function testCount()
	{
		$obj = new BasicTypedClass();
		$this->assertCount(6, $obj);
	}

	public function testGetPublicNames()
	{
		$obj   = new BasicTypedClass();
		$names = $obj->getPublicNames();
		$this->assertContains('intVal', $names);
		$this->assertContains('strVal', $names);
		$this->assertContains('nullableInt', $names);
		$this->assertNotContains('_map', $names);
		$this->assertNotContains('conversionOptions', $names);
	}

	// === __isset / __unset ===

	public function testIsset()
	{
		$obj = new BasicTypedClass(['intVal' => 5]);
		$this->assertTrue(isset($obj->intVal));
		$this->assertFalse(isset($obj->nullableInt)); // null
	}

	public function testUnset()
	{
		$obj = new BasicTypedClass(['intVal' => 42]);
		unset($obj->intVal);
		$this->assertSame(0, $obj->intVal); // reset to zero, not removed
	}

	// === _massageInput ===

	public function testAssignInvalidJsonStringThrows()
	{
		$this->expectException(JsonException::class);
		$obj = new BasicTypedClass();
		$obj->assign('not valid json');
	}

	// === Type coercion edge cases ===

	public function testArrayToInt()
	{
		$obj         = new BasicTypedClass();
		$obj->intVal = [1, 2, 3];
		$this->assertSame(3, $obj->intVal); // count of array
	}

	public function testObjectToString()
	{
		$obj         = new BasicTypedClass();
		$o           = new stdClass();
		$o->key      = 'val';
		$obj->strVal = $o;
		$this->assertSame('{"key":"val"}', $obj->strVal);
	}

	// === _massageInput additional paths ===

	public function testAssignNull()
	{
		$obj = new BasicTypedClass(['intVal' => 5]);
		$obj->assign(null);
		// null → empty array, so nothing changes
		$this->assertSame(5, $obj->intVal);
	}

	public function testAssignEmptyString()
	{
		$obj = new BasicTypedClass(['intVal' => 5]);
		$obj->assign('');
		// empty string → empty array, so nothing changes
		$this->assertSame(5, $obj->intVal);
	}

	public function testAssignJsonString()
	{
		$obj = new BasicTypedClass();
		$obj->assign('{"intVal": 42, "strVal": "hello"}');
		$this->assertSame(42, $obj->intVal);
		$this->assertSame('hello', $obj->strVal);
	}

	public function testAssignFalse()
	{
		$obj = new BasicTypedClass(['intVal' => 5]);
		$obj->assign(false);
		// false → empty array, nothing changes
		$this->assertSame(5, $obj->intVal);
	}

	public function testAssignTrueThrows()
	{
		$this->expectException(InvalidArgumentException::class);
		$obj = new BasicTypedClass();
		$obj->assign(true);
	}

	public function testAssignIntThrows()
	{
		$this->expectException(InvalidArgumentException::class);
		$obj = new BasicTypedClass();
		$obj->assign(42);
	}

	public function testAssignFloatThrows()
	{
		$this->expectException(InvalidArgumentException::class);
		$obj = new BasicTypedClass();
		$obj->assign(3.14);
	}

	// === _setBasicTypeAndConfirm additional paths ===

	public function testStringableObjectToString()
	{
		$obj         = new BasicTypedClass();
		$stringable  = new class {
			public function __toString(): string { return 'stringable!'; }
		};
		$obj->strVal = $stringable;
		$this->assertSame('stringable!', $obj->strVal);
	}

	public function testArrayToString()
	{
		$obj         = new BasicTypedClass();
		$obj->strVal = ['a', 'b', 'c'];
		$this->assertSame('["a","b","c"]', $obj->strVal);
	}

	public function testArrayToBool()
	{
		$obj          = new BasicTypedClass();
		$obj->boolVal = [1, 2];
		$this->assertTrue($obj->boolVal);

		$obj->boolVal = [];
		$this->assertFalse($obj->boolVal);
	}

	public function testObjectToBool()
	{
		$obj          = new BasicTypedClass();
		$o            = new stdClass();
		$o->key       = 'val';
		$obj->boolVal = $o;
		$this->assertTrue($obj->boolVal);

		$empty        = new stdClass();
		$obj->boolVal = $empty;
		$this->assertFalse($obj->boolVal);
	}

	public function testObjectToInt()
	{
		$obj         = new BasicTypedClass();
		$o           = new stdClass();
		$o->a        = 1;
		$o->b        = 2;
		$obj->intVal = $o;
		// object cast to array, then count
		$this->assertSame(2, $obj->intVal);
	}

	public function testArrayToFloat()
	{
		$obj           = new BasicTypedClass();
		$obj->floatVal = [1, 2, 3];
		// (float) on array — this is a PHP cast
		$this->assertIsFloat($obj->floatVal);
	}
}
