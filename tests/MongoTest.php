<?php

use Diskerror\Typed\ArrayOptions as AO;

class MongoTest extends PHPUnit\Framework\TestCase
{
	public function testTweet()
	{
		date_default_timezone_set('UTC');

		$tweet = new Tweet();

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

//		$tweet->setArrayOptions(0);

//		jsonPrint($tweet);exit;
//		$this->assertJsonStringEqualsJsonFile(
//			__DIR__ . '/results/mongo3.json',
//			json_encode($tweet)
//		);
	}

	public function testConfig()
	{
		$config = new MongoConfig([
			'host'        => 'mongodb://127.0.0.1:27017',
			'database'    => 'master_db',
			'collections' => [
				'tweet' => [
					['keys' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 60 * 30]]
				],
				'invoice_item'  => [
					[['invoice_number' => 1, 'company_number' => 1, 'item_id' => 1], ['unique' => true]],
					[['received_on' => 1]],
					[['errors' => 1]],
				],
				'error_message'      => [
					['keys' => ['occured_on' => 1], 'options' => ['expireAfterSeconds' => 60 * 60 * 24 * 7]],
					['keys' => ['message' => 1]],
					['keys' => ['code' => 1]],
				],
			],
		]);

//		jsonPrint($tweet);exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo_config.json',
			json_encode($config)
		);
	}

}
