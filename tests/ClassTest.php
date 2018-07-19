<?php

class ClassTest extends PHPUnit\Framework\TestCase
{
	public function testNewTypedDate()
	{
		$d = new TypedDate();

		$d->date = 'Feb 1, 2015';
		$this->assertEquals(new DateTime('Feb 1, 2015'), $d->date);
	}

	/**
	 * @depends                        testNewTypedDate
	 * @expectedException            Exception
	 * @expectedExceptionMessage       DateTime::__construct(): Failed to parse time string (77) at position 0 (7):
	 *                                Unexpected character
	 */
	public function testBadDateValue()
	{
		$d       = new TypedDate();
		$d->date = 77;
	}

	/**
	 * @depends                        testNewTypedDate
	 * @expectedException            InvalidArgumentException
	 * @expectedExceptionMessage       cannot coerce object types
	 */
	public function testBadDateClass()
	{
		$d          = new TypedDate();
		$c          = new stdClass();
		$c->aMember = 'string data';
		$d->date    = $c;
	}

	/**
	 * @depends    testNewTypedDate
	 */
	public function testNested()
	{
		$n          = new Nested();
		$n->d->date = '2/2/15';

		$this->assertEquals('2015-01-01', $n->date->format('Y-m-d'));
		$this->assertEquals('20150202', $n->d->date->format('Ymd'));
	}

	/**
	 * @depends    testNewTypedDate
	 */
	public function testRange()
	{
		$dr        = new DateRange();
		$dr->start = '20150202';
		$dr->end   = '20150101';
		$this->assertEquals('2015-01-01', $dr->start->format('Y-m-d'));
	}

}


////////////////////////////////////////////////////////////////////////////////////////////////////
class TypedDate extends \Diskerror\Typed\TypedClass
{
	protected $date = '__class__DateTime';
}

class Nested extends \Diskerror\Typed\TypedClass
{
	protected $name = 'secret';

	protected $d    = '__class__TypedDate';

	protected $date = '__class__DateTime("Jan 1, 2015")';
}

class DateRange extends \Diskerror\Typed\TypedClass
{
	protected $start = '__class__DateTime';

	protected $end   = '__class__DateTime';

	protected function _checkRelatedProperties()
	{
		if ($this->start > $this->end) {
			$this->start = clone $this->end;
		}
	}
}
