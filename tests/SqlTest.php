<?php

require_once __DIR__ . '/classes/SimpleTyped.php';

class SqlTest extends PHPUnit_Framework_TestCase
{
	public function testSql()
	{
		$simp = new SimpleTyped();

// 		echo "%s\n", \Diskerror\Typed\SqlStatement::toInsert($simp->toArray()); exit;
		$this->assertEquals('`myBool` = 1,
`myInt` = 0,
`myFloat` = 3.14,
`myString` = "",
`myArray` = "[]",
`myObj` = "[]",
`myTypedArray` = "[]"',
			\Diskerror\Typed\SqlStatement::toInsert($simp->toArray())
		);


// 		echo "%s\n", $ss->getSqlValues(); exit;
		$this->assertEquals('`myBool` = VALUES(`myBool`),
`myInt` = VALUES(`myInt`),
`myFloat` = VALUES(`myFloat`),
`myString` = VALUES(`myString`),
`myArray` = VALUES(`myArray`),
`myObj` = VALUES(`myObj`),
`myTypedArray` = VALUES(`myTypedArray`)',
			\Diskerror\Typed\SqlStatement::toValues($simp->toArray())
		);
	}

}
