<?php

require_once __DIR__ . '/classes/PrefListList.php';

class PrefListTest extends PHPUnit_Framework_TestCase
{
	public function testComplex()
	{
		$prefListList = PrefListList::getDefault();
		// echo jsonEncode($prefListList->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefone.json',
			json_encode($prefListList->toArray()),
			'Creation of complex object.'
		);


		$prefListList['Option ZIP']['name'] = ['sort'=>'desc'];
		// echo jsonEncode($prefListList['Option ZIP']->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/prefoptname.json',
			json_encode($prefListList['Option ZIP']->toArray()),
			'Added simple object to deeply nested object.'
		);


		$prefListList['Option ZIP'] = ['state'=>['included'=>1], 'name'=>['compare'=>'!=']];
		// echo jsonEncode($prefListList->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preftwo.json',
			json_encode($prefListList->toArray()),
			'Added complex object to contained member.'
		);


		$order = ['zip', 'name', 'address', 'city', 'state'];
		$oldOrder = clone $prefListList['Option ZIP'];
		$prefListList['Option ZIP'] = null;

		foreach ( $order as $o ) {
			$prefListList['Option ZIP'][$o] = $oldOrder[$o];
		}
		// echo jsonEncode($prefListList['Option ZIP']->toArray()); exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/preforder.json',
			json_encode($prefListList['Option ZIP']->toArray()),
			'Reorder contained list.'
		);
	}

}