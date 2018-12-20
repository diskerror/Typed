<?php

class PrefListTest extends PHPUnit\Framework\TestCase
{
	public function testComplex()
	{
		$prefListList = PrefListList::getDefault();
//		jsonPrint($prefListList); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefone.json',
			json_encode($prefListList),
			'Creation of complex object.'
		);

		$prefListList['Option ZIP']['name'] = ['sort' => 'desc'];
//		jsonPrint($prefListList['Option ZIP']); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefoptname.json',
			json_encode($prefListList['Option ZIP']),
			'Added simple object to deeply nested object.'
		);


		$prefListList['Option ZIP']->replace(['state' => ['included' => 1], 'name' => ['compare' => '!=']]);
//		jsonPrint($prefListList); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preftwo.json',
			json_encode($prefListList),
			'Added complex object to contained member.'
		);


		$order                      = ['zip', 'name', 'address', 'city', 'state'];
		$oldOrder                   = clone $prefListList['Option ZIP'];
		$prefListList['Option ZIP'] = null;

		foreach ($order as $o) {
			$prefListList['Option ZIP'][$o] = $oldOrder[$o];
		}
//		jsonPrint($prefListList['Option ZIP']); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preforder.json',
			json_encode($prefListList['Option ZIP']),
			'Reorder contained list.'
		);
	}

}
