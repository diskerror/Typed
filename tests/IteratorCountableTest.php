<?php

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use PHPUnit\Framework\TestCase;

class CountableTestClass extends TypedClass
{
    protected int $a = 1;
    protected int $b = 2;
    protected int $c = 3;
}

class IteratorCountableTest extends TestCase
{
    public function testTypedClassCount()
    {
        $obj = new CountableTestClass();
        // Should count the number of "managed" properties (public + protected/private valid ones)
        $this->assertCount(3, $obj);
    }

    public function testTypedClassIterator()
    {
        $obj = new CountableTestClass();
        $results = [];
        foreach ($obj as $key => $val) {
            $results[$key] = $val;
        }

        $expected = ['a' => 1, 'b' => 2, 'c' => 3];
        $this->assertEquals($expected, $results);
    }

    public function testTypedArrayCount()
    {
        $arr = new TypedArray('string', ['one', 'two', 'three']);
        $this->assertCount(3, $arr);
        $this->assertSame(3, count($arr));
    }

    public function testTypedArrayIterator()
    {
        $arr = new TypedArray('int', [10, 20, 30]);
        $sum = 0;
        foreach ($arr as $val) {
            $sum += $val;
        }
        $this->assertSame(60, $sum);
    }
    
    public function testModificationDuringIteration()
    {
        // TypedArray supports modification during iteration if set up correctly
        $arr = new TypedArray('int', [1, 2, 3]);
        foreach ($arr as $k => $v) {
             // This is a test to ensure no errors occur, although standard foreach on objects usually works on a copy or iterator
             $arr[$k] = $v * 2;
        }
        
        $this->assertSame(2, $arr[0]);
        $this->assertSame(4, $arr[1]);
        $this->assertSame(6, $arr[2]);
    }
}
