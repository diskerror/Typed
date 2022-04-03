<?php

use TestClasses\Preferences\Item;
use TestClasses\Preferences\ItemList;
use TestClasses\Preferences\ListList;

class PrefListTest extends PHPUnit\Framework\TestCase
{
	public function testComplex()
	{
		$itemVals = new Item(['sort' => 'DESC']);
		$itemListVals = new ItemList([['find'=>'the name'], ['find'=>'an address']]);

		$prefListList = ListList::getDefault();
//		jsonPrint($prefListList->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefone.json',
			json_encode($prefListList->toArray()),
			'Creation of complex object.'
		);

//		$prefListList['Option ZIP']['name'] = ['sort' => 'desc'];
////		jsonPrint($prefListList['Option ZIP']->toArray()); exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/prefoptname.json',
//			json_encode($prefListList['Option ZIP']->toArray()),
//			'Added simple object to deeply nested object.'
//		);
//
//
//		$prefListList['Option ZIP']->replace(['state' => ['included' => 1], 'name' => ['compare' => '!=']]);
////		jsonPrint($prefListList->toArray()); exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/preftwo.json',
//			json_encode($prefListList->toArray()),
//			'Added complex object to contained member.'
//		);
//
//
//		$order                      = ['zip', 'name', 'address', 'city', 'state'];
//		$oldOrder                   = clone $prefListList['Option ZIP'];
//		$prefListList['Option ZIP'] = null;
//
//		foreach ($order as $o) {
//			$prefListList['Option ZIP'][$o] = $oldOrder[$o];
//		}
////		jsonPrint($prefListList['Option ZIP']->toArray()); exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/preforder.json',
//			json_encode($prefListList['Option ZIP']->toArray()),
//			'Reorder contained list.'
//		);
	}

}
