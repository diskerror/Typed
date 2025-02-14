<?php

use Diskerror\Typed\BSON\DateTime;
use Diskerror\Typed\ConversionOptions;
use MongoDB\BSON\Document;
use MongoDB\BSON\UTCDateTime;
use TestClasses\Mongo\Config;
use TestClasses\Mongo\Tweet;

class MongoTest extends PHPUnit\Framework\TestCase
{
	public function testTweet()
	{
		$tweet = new Tweet();
        $tweet->conversionOptions->set(ConversionOptions::OMIT_EMPTY);
        $tweet->setConversionOptionsToNested();

//		jsprint($tweet->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo1.json',
			json_encode($tweet)
		);

//		jsprint($tweet->bsonSerialize());exit;
//		file_put_contents(__DIR__.'/results/mongo3.json', json_encode($tweet->bsonSerialize(), JSON_PRETTY_PRINT));
//		fwrite(STDERR, fromPHP($tweet->bsonSerialize()));exit;
//		file_put_contents(__DIR__.'/results/mongo3.bson', fromPHP($tweet));
//		var_export($tweet->bsonSerialize());exit;
//		file_put_contents(__DIR__.'/results/mongo3.php', var_export($tweet, true));
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo3.json',
			json_encode($tweet->bsonSerialize())
		);
		$this->assertStringEqualsFile(
			__DIR__ . '/results/mongo3.bson',
			Document::fromPHP($tweet)
		);
	}

	public function testEmptyConfig()
	{
		$empty = new Config();
        $empty->conversionOptions->set(ConversionOptions::OMIT_EMPTY);
        $empty->setConversionOptionsToNested();

//		jsprint($empty->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/mongo_empty_config.json',
			json_encode($empty)
		);
	}

    public function testConfig()
    {
        $config = new Config(
            [
                'host' => 'mongodb://127.0.0.1:27017',
                'database' => 'master_db',
                'collections' => [
                    'tweet' => [
                        ['keys' => ['created_at' => 1], 'options' => ['expireAfterSeconds' => 60 * 30]],
                    ],
                    'invoice_item' => [
                        /* These should set by position. */
                        [['invoice_number' => 1, 'company_number' => 1, 'item_id' => 1], ['unique' => true]],
                        [['received_on' => 1]],
                        [['errors' => 1]],
                    ],
                    'error_message' => [
                        ['keys' => ['occured_on' => 1], 'options' => ['expireAfterSeconds' => 60 * 60 * 24 * 7]],
                        ['keys' => ['message' => 1]],
                        ['keys' => ['code' => 1]],
                    ],
                ],
            ]
        );
        $config->conversionOptions->set(ConversionOptions::OMIT_EMPTY);
        $config->setConversionOptionsToNested();

//		jsprint($config->toArray());exit;
        $this->assertJsonStringEqualsJsonFile(
            __DIR__ . '/results/mongo_config.json',
            json_encode($config)
        );
    }

	public function testBsonDate()
	{
		$mbdt = new UTCDateTime(1561431851340);
		$dt   = new DateTime(1561431851.34);

		$this->assertEquals(
            (string)$dt,
            (string)((array)$mbdt->toDateTime())['date']
		);

		$mbdt2 = new UTCDateTime(1561431851340);
		$dt2   = new DateTime($mbdt2);

//		fwrite(STDERR, $dt2->__toString());exit;
		$this->assertEquals(
            (string)$dt2,
            (string)((array)$mbdt2->toDateTime())['date']
        );
	}

}
