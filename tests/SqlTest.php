<?php

use Diskerror\Typed\SqlStatement;

class SqlTest extends PHPUnit\Framework\TestCase
{
	public function testSql()
	{
		$simp = new SimpleTyped();

// 		echo "%s\n", SqlStatement::toInsert($simp->toArray()); exit;
		$this->assertEquals('`myBool` = 1,
`myInt` = 0,
`myFloat` = 3.14,
`myString` = "",
`myArray` = "[]",
`myObj` = "{}",
`myDate` = "2010-01-01 01:01:01.001",
`myTypedArray` = "[]"',
			SqlStatement::toInsert($simp->toArray())
		);


// 		printf("%s\n", SqlStatement::toValues($simp)); exit;
		$this->assertEquals('`myBool` = VALUES(`myBool`),
`myInt` = VALUES(`myInt`),
`myFloat` = VALUES(`myFloat`),
`myString` = VALUES(`myString`),
`myArray` = VALUES(`myArray`),
`myObj` = VALUES(`myObj`),
`myDate` = VALUES(`myDate`),
`myTypedArray` = VALUES(`myTypedArray`)',
			SqlStatement::toValues($simp)
		);
	}

}
