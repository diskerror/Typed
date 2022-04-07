<?php

require_once __DIR__ . '/bootstrap.php';

use TestClasses\Tweet;

class SerializeTest extends PHPUnit\Framework\TestCase
{
	public function testSerialize()
	{
		$tweet = new Tweet();

		$serialized = serialize($tweet);

		$unserialized = unserialize($serialized);

//		jsonPrint($tweet);
//		jsonPrint($unserialized);

		$this->assertTrue($tweet == $unserialized);
		$this->assertFalse($tweet === $unserialized);
	}

	public function testNewTweet()
	{
		$tweetString = file_get_contents(__DIR__ . '/data/tweet.json');
		$tweet       = new Tweet($tweetString);
//		jsonPrint($tweet->toArray());exit;
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/tweetnew.json',
			json_encode($tweet),
			'New tweet with initial data.'
		);
	}

	public function testAssign()
	{
		$tweetString = file_get_contents(__DIR__ . '/data/tweet.json');
		$tweet       = new Tweet();
		$tweet->assign($tweetString);
//		jsonPrint($tweet->toArray());
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/tweetnew.json',
			json_encode($tweet->toArray()),
			'Tweet with assigned data.'
		);
	}

	public function testReplace()
	{
		$tweetString = file_get_contents(__DIR__ . '/data/tweet2.json');
		$tweet       = new Tweet();
		$tweet->replace($tweetString);
//		jsonPrint($tweet->toArray());
		$this->assertJsonStringEqualsJsonFile(
			__DIR__ . '/results/tweetreplace.json',
			json_encode($tweet->toArray()),
			'Tweet with replacement data.'
		);
	}

	public function testDate(){
		$dt = new \Diskerror\Typed\DateTime(1561431851.34);

//		jsonPrint($dt->__tostring());exit;
		$this->assertEquals(
			'2019-06-25 03:04:11.340000',
			$dt->format(\Diskerror\Typed\DateTime::MYSQL_STRING_IO_FORMAT_MICRO)
		);
	}

}
