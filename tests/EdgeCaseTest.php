<?php

use Diskerror\Typed\TypedClass;
use PHPUnit\Framework\TestCase;

class EdgeCaseTestClass extends TypedClass
{
    protected ?int $nullableInt = null;
    protected int $strictInt = 0;
    protected ?string $nullableString = null;
    protected string $strictString = '';
    protected bool $boolVal = false;
    protected float $floatVal = 0.0;
}

class EdgeCaseTest extends TestCase
{
    public function testNullableHandling()
    {
        $obj = new EdgeCaseTestClass();
        
        // Test null assignment to nullable
        $obj->assign(['nullableInt' => null]);
        $this->assertNull($obj->nullableInt);

        // Test empty string to nullable int (should be null or 0?)
        // Based on TInteger logic: empty string -> null if nullable
        $obj->assign(['nullableInt' => '']);
        $this->assertNull($obj->nullableInt);
    }

    public function testStrictHandling()
    {
        $obj = new EdgeCaseTestClass();

        // Test null assignment to strict (should become 0)
        $obj->assign(['strictInt' => null]);
        $this->assertSame(0, $obj->strictInt);

        // Test empty string to strict int
        $obj->assign(['strictInt' => '']);
        $this->assertSame(0, $obj->strictInt);
    }

    public function testBooleanCoercion()
    {
        $obj = new EdgeCaseTestClass();

        // String "false" is true in PHP, but let's see how the library handles it via settype logic
        // The library uses settype or specific scalar classes.
        
        $obj->assign(['boolVal' => '1']);
        $this->assertTrue($obj->boolVal);

        $obj->assign(['boolVal' => 0]);
        $this->assertFalse($obj->boolVal);
        
        $obj->assign(['boolVal' => '']);
        $this->assertFalse($obj->boolVal);
    }

    public function testFloatCoercion()
    {
        $obj = new EdgeCaseTestClass();
        
        $obj->assign(['floatVal' => '123.45']);
        $this->assertSame(123.45, $obj->floatVal);
        
        $obj->assign(['floatVal' => '']);
        $this->assertSame(0.0, $obj->floatVal);
    }

    public function testInvalidInputTypes()
    {
        $obj = new EdgeCaseTestClass();
        
        // Assigning an array to an int property - should likely become count(array) or 0 or 1 depending on logic
        // TypedAbstract::_setBasicTypeAndConfirm says for integer: case 'array': $val = count($val);
        $obj->assign(['strictInt' => [1, 2, 3]]);
        $this->assertSame(3, $obj->strictInt);
    }
}
