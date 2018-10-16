<?php

class SerializeTest extends PHPUnit\Framework\TestCase
{
	public function testSerialize()
	{
		$tweet = new Tweet();

		$serialized = serialize($tweet);

		$unserialized = unserialize($serialized);

		$this->assertTrue($tweet == $unserialized);
		$this->assertFalse($tweet === $unserialized);
	}

}
