<?php

use Diskerror\Typed\ArrayOptions as AO;

class MongoTest extends PHPUnit\Framework\TestCase
{
	public function testMongo()
	{
		$tweet = new Tweet();
		$tweet->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::SWITCH_ID | AO::TO_BSON_DATE | AO::SWITCH_NESTED_ID);

//		jsonPrint($tweet);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo1.json',
			json_encode($tweet)
		);

		$tweet->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::SWITCH_ID);

//		jsonPrint($tweet);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo2.json',
			json_encode($tweet)
		);

		$tweet->setArrayOptions(0);

//		jsonPrint($tweet);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo3.json',
			json_encode($tweet)
		);
	}

}
