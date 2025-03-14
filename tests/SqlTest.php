<?php

use Diskerror\Typed\ConversionOptions;
use Diskerror\Typed\SqlStatement;
use PHPUnit\Framework\TestCase;
use TestClasses\SimpleTyped;

class SqlTest extends TestCase
{
	public function testSql()
	{
		$simp = new SimpleTyped();
        $simp->conversionOptions->set(ConversionOptions::DATE_TO_STRING);

// 		echo "%s\n", SqlStatement::toInsert($simp->toArray()); exit;
		$this->assertEquals('`myBool` = 1,
`myInt` = 0,
`myFloat` = 3.14,
`myString` = "",
`myArray` = "[]",
`myObj` = "[]",
`myDate` = "2010-01-01 01:01:01.001000",
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
