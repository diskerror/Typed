<?php

use Diskerror\Typed\TypedArray;
use Diskerror\Typed\TypedClass;
use PHPUnit\Framework\TestCase;

class InnerItem extends TypedClass
{
    protected string $name = '';
}

class OuterContainer extends TypedClass
{
    protected InnerItem $inner;
    protected TypedArray $list; // Will need to be initialized in constructor usually, or via library magic
    
    protected function _initProperties(): void
    {
        $this->inner = new InnerItem();
        $this->list = new TypedArray('int');
    }
}

class NestedStructureTest extends TestCase
{
    public function testDeepAssignment()
    {
        $data = [
            'inner' => ['name' => 'Deep Name'],
            'list' => [1, '2', 3.5]
        ];

        $container = new OuterContainer($data);

        $this->assertEquals('Deep Name', $container->inner->name);
        $this->assertSame(1, $container->list[0]);
        $this->assertSame(2, $container->list[1]); // '2' -> 2
        $this->assertSame(3, $container->list[2]); // 3.5 -> 3 (int cast)
    }

    public function testMergeDeepCopy()
    {
        $container = new OuterContainer([
            'inner' => ['name' => 'Original'],
            'list' => [10]
        ]);

        $newData = [
            'inner' => ['name' => 'New Name']
        ];

        // Merge returns a NEW object
        $merged = $container->merge($newData);

        // Verify merged object
        $this->assertEquals('New Name', $merged->inner->name);
        $this->assertSame(10, $merged->list[0]);

        // Verify original object is untouched (Deep Copy check)
        $this->assertEquals('Original', $container->inner->name);
    }
}
