<?php
/** @noinspection ALL */
/** @noinspection ALL */

use PHPUnit\Framework\TestCase;
use TestClasses\Preferences\{Item, ItemList, ListList};

class PrefListTest extends TestCase
{
	public function testComplex()
	{
		$itemVals     = new Item(['sort' => 'DESC']);
		$itemListVals = new ItemList([['find' => 'the name'], ['find' => 'an address']]);

		$prefListList = ListList::getDefault();
//		tprint($prefListList->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefone.json',
			json_encode($prefListList->toArray()),
			'Creation of complex object.'
		);

		$prefListList['Option ZIP']['name'] = ['sort' => 'desc'];
//		tprint($prefListList['Option ZIP']->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefoptname.json',
			json_encode($prefListList['Option ZIP']->toArray()),
			'Added simple object to deeply nested object.'
		);


		$prefListList['Option ZIP']->replace(['state' => ['included' => 1], 'name' => ['compare' => '!=']]);
//		tprint($prefListList->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preftwo.json',
			json_encode($prefListList->toArray()),
			'Added complex object to contained member.'
		);


		$order                      = ['zip', 'name', 'address', 'city', 'state'];
		$oldOrder                   = clone $prefListList['Option ZIP'];
		$prefListList['Option ZIP'] = null;

		foreach ($order as $o) {
			$prefListList['Option ZIP'][$o] = $oldOrder[$o];
		}
//		tprint($prefListList['Option ZIP']->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preforder.json',
			json_encode($prefListList['Option ZIP']->toArray()),
			'Reorder contained list.'
		);
	}

}
