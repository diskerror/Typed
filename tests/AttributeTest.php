<?php

use PHPUnit\Framework\TestCase;
use TestClasses\AttributeTestClass;

class AttributeTest extends TestCase
{
    public function testAttributeMapping()
    {
        $data = [
            'user_id' => 123,
            'user_name' => 'John Doe',
            'user_email' => 'john@example.com'
        ];

        $obj = new AttributeTestClass($data);

        $this->assertEquals(123, $obj->userId);
        $this->assertEquals('John Doe', $obj->userName);
        $this->assertEquals('john@example.com', $obj->email);
    }
}
