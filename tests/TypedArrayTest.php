<?php

use Diskerror\Typed\ConversionOptions;
use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use Diskerror\Typed\Scalar\TInteger;
use PHPUnit\Framework\TestCase;

class TypedArrayTestItem extends TypedClass
{
    protected string $name = '';
    protected int $value = 0;
}

class TypedArrayTest extends TestCase
{
    // === Construction ===

    public function testDirectInstantiationWithType()
    {
        $arr = new TypedArray('int', [1, '2', 3.7]);
        $this->assertCount(3, $arr);
        $this->assertSame(1, $arr[0]);
        $this->assertSame(2, $arr[1]);
        $this->assertSame(3, $arr[2]);
    }

    public function testDirectInstantiationNoType()
    {
        $arr = new TypedArray('', ['a', 1, true]);
        $this->assertCount(3, $arr);
        $this->assertSame('a', $arr[0]);
        $this->assertSame(1, $arr[1]);
        $this->assertTrue($arr[2]);
    }

    public function testNullAssignment()
    {
        $arr = new TypedArray('string', ['a', 'b']);
        $arr->assign(null);
        $this->assertCount(0, $arr);
    }

    // === Type coercion ===

    public function testStringCoercion()
    {
        $arr = new TypedArray('string', [42, true, 3.14]);
        $this->assertSame('42', $arr[0]);
        $this->assertSame('1', $arr[1]);
        $this->assertSame('3.14', $arr[2]);
    }

    public function testBoolCoercion()
    {
        $arr = new TypedArray('bool', [0, 1, '', 'yes']);
        $this->assertFalse($arr[0]);
        $this->assertTrue($arr[1]);
        $this->assertFalse($arr[2]);
        $this->assertTrue($arr[3]);
    }

    // === ArrayAccess ===

    public function testOffsetExists()
    {
        $arr = new TypedArray('int', [10, 20, 30]);
        $this->assertTrue(isset($arr[0]));
        $this->assertTrue(isset($arr[2]));
        $this->assertFalse(isset($arr[5]));
    }

    public function testOffsetUnset()
    {
        $arr = new TypedArray('int', [10, 20, 30]);
        unset($arr[1]);
        $this->assertCount(2, $arr);
        $this->assertFalse(isset($arr[1]));
    }

    public function testOffsetGetAutoCreates()
    {
        $arr = new TypedArray('int');
        $val = $arr['val'];
        $this->assertFalse(isset($arr['val']));
        $this->assertSame(null, $val);
    }

    public function testAssociativeKeys()
    {
        $arr = new TypedArray('string');
        $arr['foo'] = 'bar';
        $arr['baz'] = 'qux';
        $this->assertSame('bar', $arr['foo']);
        $this->assertSame('qux', $arr['baz']);
        $this->assertEquals(['foo', 'baz'], $arr->keys());
    }

    // === TypedArray with objects ===

    public function testObjectArray()
    {
        $arr = new TypedArray(TypedArrayTestItem::class);
        $arr[] = ['name' => 'first', 'value' => 10];
        $arr[] = ['name' => 'second', 'value' => 20];
        $this->assertCount(2, $arr, json_encode($arr));
        $this->assertEquals('first', $arr[0]->name);
        $this->assertEquals(20, $arr[1]->value);
    }

    // === Merge ===

    public function testMergeIndexed()
    {
        $arr = new TypedArray('int', [1, 2]);
        $merged = $arr->merge(['a' => 3, 'b' => 4]);
        $this->assertCount(4, $merged);
        // Original unchanged
        $this->assertCount(2, $arr);
    }

    public function testMergeAssociative()
    {
        $arr = new TypedArray('string', ['a' => 'hello']);
        $merged = $arr->merge(['b' => 'world']);
        $this->assertSame('hello', $merged['a']);
        $this->assertSame('world', $merged['b']);
    }

    // === Clone ===

    public function testCloneDeepCopy()
    {
        $arr = new TypedArray(TypedArrayTestItem::class);
        $arr[] = ['name' => 'original', 'value' => 1];
        $clone = clone $arr;
        $clone[0]->name = 'cloned';
        $this->assertEquals('original', $arr[0]->name);
    }

    // === toArray / jsonSerialize ===

    public function testToArray()
    {
        $arr = new TypedArray('int', [1, 2, 3]);
        $this->assertSame([1, 2, 3], $arr->toArray());
    }

    public function testJsonEncode()
    {
        $arr = new TypedArray('string', ['a', 'b']);
        $this->assertSame('["a","b"]', json_encode($arr));
    }

    // === clear ===

    public function testClear()
    {
        $arr = new TypedArray('int', [1, 2, 3]);
        $arr->clear();
        $this->assertCount(0, $arr);
    }

    // === combine ===

    public function testCombine()
    {
        $arr = new TypedArray('int', [10, 20, 30]);
        $arr->combine(['a', 'b', 'c']);
        $this->assertSame(10, $arr['a']);
        $this->assertSame(30, $arr['c']);
    }

    public function testCombineLengthMismatch()
    {
        $this->expectException(LengthException::class);
        $arr = new TypedArray('int', [1, 2]);
        $arr->combine(['a']);
    }

    // === shift / values ===

    public function testShift()
    {
        $arr = new TypedArray('string', ['first', 'second']);
        $val = $arr->shift();
        $this->assertSame('first', $val);
        $this->assertCount(1, $arr);
    }

    public function testValues()
    {
        $arr = new TypedArray('int');
        $arr['x'] = 1;
        $arr['y'] = 2;
        $this->assertSame([1, 2], $arr->values());
    }

    public function testOffsetSetWithTypedAbstractType()
    {
        $arr = new TypedArray(TypedArrayTestItem::class);
        $arr[] = ['name' => 'first', 'value' => 1];
        
        $arr[0] = ['name' => 'updated', 'value' => 2];
        $this->assertEquals('updated', $arr[0]->name);
        $this->assertEquals(2, $arr[0]->value);
    }

    public function testOffsetGetAtomicInterfaceNeverUnwraps()
    {
        $arr = new TypedArray(TInteger::class);
        $arr[] = 42;
        
        $val = $arr[0];
        $this->assertEquals(42, $val);
    }

    // === JSON input ===

    public function testAssignFromJsonString()
    {
        $arr = new TypedArray('int');
        $arr->assign('[1, 2, 3]');
        $this->assertCount(3, $arr);
        $this->assertSame(1, $arr[0]);
    }

    // === OMIT_EMPTY ===

    public function testToArrayOmitEmpty()
    {
        $arr = new TypedArray('string', ['hello', '', 'world', '']);
        $arr->conversionOptions->add(ConversionOptions::OMIT_EMPTY);
        $result = $arr->toArray();
        // After removing empties from indexed array, re-indexing happens
        $this->assertSame(['hello', 'world'], $result);
    }
}
