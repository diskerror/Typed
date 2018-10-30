<?php

use Diskerror\Typed\ArrayOptions as AO;

class MongoTest extends PHPUnit\Framework\TestCase
{
	public function testMongo()
	{
		date_default_timezone_set('UTC');

		$tweet = new Tweet();
		$tweet->setArrayOptions(AO::OMIT_EMPTY | AO::OMIT_RESOURCE | AO::TO_BSON_DATE);

//		jsonPrint($tweet);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo1.json',
			json_encode($tweet)
		);

//		jsonPrint($tweet);exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/mongo2.json',
//			json_encode($tweet)
//		);

		$tweet->setArrayOptions(0);

//		jsonPrint($tweet);exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/mongo3.json',
//			json_encode($tweet)
//		);
	}

}
