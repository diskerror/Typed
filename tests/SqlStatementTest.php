<?php

use Diskerror\Typed\SqlStatement;
use PHPUnit\Framework\TestCase;

class SqlStatementTest extends TestCase
{
    // === toInsert ===

    public function testToInsertBasicTypes()
    {
        $input = [
            'name' => 'Alice',
            'age' => 25,
            'score' => 99.5,
            'active' => true,
            'inactive' => false,
        ];
        $result = SqlStatement::toInsert($input);
        $this->assertStringContainsString('`name` = "Alice"', $result);
        $this->assertStringContainsString('`age` = 25', $result);
        $this->assertStringContainsString('`score` = 99.5', $result);
        $this->assertStringContainsString('`active` = 1', $result);
        $this->assertStringContainsString('`inactive` = 0', $result);
    }

    public function testToInsertNullValue()
    {
        $result = SqlStatement::toInsert(['field' => null]);
        $this->assertEquals('`field` = NULL', $result);
    }

    public function testToInsertStringNull()
    {
        $result = SqlStatement::toInsert(['field' => 'NULL']);
        $this->assertEquals('`field` = NULL', $result);
    }

    public function testToInsertEscaping()
    {
        $result = SqlStatement::toInsert(['name' => "O'Brien"]);
        $this->assertStringContainsString("O\\'Brien", $result);
    }

    public function testToInsertArray()
    {
        $result = SqlStatement::toInsert(['data' => ['a', 'b']]);
        $this->assertStringContainsString('`data` = "[\\"a\\",\\"b\\"]"', $result);
    }

    public function testToInsertFromObject()
    {
        $obj = new stdClass();
        $obj->name = 'test';
        $obj->value = 42;
        $result = SqlStatement::toInsert($obj);
        $this->assertStringContainsString('`name` = "test"', $result);
        $this->assertStringContainsString('`value` = 42', $result);
    }

    public function testToInsertBadInputThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        SqlStatement::toInsert(42);
    }

	// === toValues ===

    public function testToValues()
    {
        $input = ['name' => 'x', 'age' => 1];
        $result = SqlStatement::toValues($input);
        $this->assertStringContainsString('`name` = VALUES(`name`)', $result);
        $this->assertStringContainsString('`age` = VALUES(`age`)', $result);
    }

    public function testToValuesWithInclude()
    {
        $input = ['name' => 'x', 'age' => 1, 'email' => 'y'];
        $result = SqlStatement::toValues($input, ['name', 'email']);
        $this->assertStringContainsString('`name` = VALUES(`name`)', $result);
        $this->assertStringContainsString('`email` = VALUES(`email`)', $result);
        $this->assertStringNotContainsString('`age`', $result);
    }

    public function testToValuesBadInput()
    {
        $this->expectException(InvalidArgumentException::class);
        SqlStatement::toValues('bad');
    }
}
