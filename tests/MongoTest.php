<?php

use Diskerror\Typed\ArrayOptions as AO;

class MongoTest extends PHPUnit\Framework\TestCase
{
	public function testTweet()
	{
		date_default_timezone_set('UTC');

		$tweet = new Tweet();

//		jsonPrint($tweet->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo1.json',
			json_encode($tweet->toArray())
		);

		//	"jsonSerialize" is automatically called by json_encode.
//		jsonPrint($tweet->jsonSerialize());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo2.json',
			json_encode($tweet->jsonSerialize())
		);

//		jsonPrint($tweet->bsonSerialize());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo3.json',
			json_encode($tweet->bsonSerialize())
		);
	}

//	public function testConfig()
//	{
//		$empty = new MongoConfig();
//
////		jsonPrint($empty->toArray());exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/mongo_empty_config.json',
//			json_encode($empty->toArray())
//		);
//
//		$config = new MongoConfig([
//			'host'        => 'mongodb://127.0.0.1:27017',
//			'database'    => 'master_db',
//			'collections' => [
//				'tweet'         => [
//					['keys' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 60 * 30]],
//				],
//				'invoice_item'  => [
//					/* These should set by position. */
//					[['invoice_number' => 1, 'company_number' => 1, 'item_id' => 1], ['unique' => true]],
//					[['received_on' => 1]],
//					[['errors' => 1]],
//				],
//				'error_message' => [
//					['keys' => ['occured_on' => 1], 'options' => ['expireAfterSeconds' => 60 * 60 * 24 * 7]],
//					['keys' => ['message' => 1]],
//					['keys' => ['code' => 1]],
//				],
//			],
//		]);
//
////		jsonPrint($config->toArray());exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/mongo_config.json',
//			json_encode($config->toArray())
//		);
//	}

}
