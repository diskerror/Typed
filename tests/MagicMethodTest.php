<?php

use Diskerror\Typed\AtMap;
use Diskerror\Typed\TypedClass;
use PHPUnit\Framework\TestCase;

class MagicTestClass extends TypedClass
{
    protected int $hiddenInt = 42;
    
    #[AtMap('alias_int')]
    protected int $aliasedInt = 100;
    
    public int $publicInt = 10;
}

class MagicMethodTest extends TestCase
{
    public function testGet()
    {
        $obj = new MagicTestClass();
        
        // Access protected property via __get
        $this->assertSame(42, $obj->hiddenInt);
        
        // Access via alias
        $this->assertSame(100, $obj->alias_int);
        $this->assertSame(100, $obj->aliasedInt);
    }

    public function testSet()
    {
        $obj = new MagicTestClass();
        
        // Set protected property
        $obj->hiddenInt = 99;
        $this->assertSame(99, $obj->hiddenInt);
        
        // Set via alias
        $obj->alias_int = 200;
        $this->assertSame(200, $obj->aliasedInt);
        $this->assertSame(200, $obj->alias_int);
    }

    public function testIsset()
    {
        $obj = new MagicTestClass();
        
        $this->assertTrue(isset($obj->hiddenInt));
        $this->assertTrue(isset($obj->alias_int));
        
        // Test unset property logic (if supported)
        $obj->hiddenInt = null; // Should reset to 0 (int strict)
        // isset usually checks for null. If hiddenInt is strictly int, it is 0, so isset is true?
        // Wait, TypedClass::__isset checks ($this->$pName !== null).
        // For strict int, it becomes 0. 0 !== null is true. So it is set.
        $this->assertTrue(isset($obj->hiddenInt)); 
    }

    public function testUnset()
    {
        $obj = new MagicTestClass();
        
        // __unset calls _setByName($pName, null).
        // For strict int, this sets it to 0.
        unset($obj->hiddenInt);
        $this->assertSame(0, $obj->hiddenInt);
    }
    
    public function testAccessingNonExistentProperty()
    {
        $this->expectException(InvalidArgumentException::class);
        $obj = new MagicTestClass();
        $val = $obj->doesNotExist;
    }
}
