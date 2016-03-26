<?php

require_once 'classes/SimpleTyped.php';

class SqlTest extends PHPUnit_Framework_TestCase
{
	public function testSql()
	{
		$simp = new SimpleTyped();
		$ss = new \Diskerror\Typed\SqlStatement($simp);

// 		echo "%s\n", $ss->getSqlInsert(); exit;
		$this->assertEquals( '`myBool` = 1,
`myInt` = 0,
`myFloat` = 3.14,
`myString` = "",
`myArray` = 0x5b5d,
`myObj` = 0x5b5d',
		$ss->getSqlInsert()
		);


// 		echo "%s\n", $ss->getSqlValues(); exit;
		$this->assertEquals( '`myBool` = VALUES(`myBool`),
`myInt` = VALUES(`myInt`),
`myFloat` = VALUES(`myFloat`),
`myString` = VALUES(`myString`),
`myArray` = VALUES(`myArray`),
`myObj` = VALUES(`myObj`)',
		$ss->getSqlValues()
		);
	}

}
