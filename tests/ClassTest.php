<?php
/** @noinspection ALL */
declare(strict_types = 1);

use Diskerror\Typed\DateTime;
use Diskerror\Typed\TypedClass;
use PHPUnit\Framework\TestCase;

final class ClassTest extends TestCase
{
	public function testNewTypedDate()
	{
		$d = new TypedDate();

		$d->date = 'Feb 1, 2015';
		$this->assertEquals(new DateTime('Feb 1, 2015'), $d->date);
	}

//	public function testBadDateValue()
//	{
//		$this->expectException(Exception::class);
//		$this->expectExceptionMessage("DateTime::__construct(): Failed to parse time string (77) at position 0 (7): Unexpected character");
//
//		$d       = new TypedDate();
//		$d->date = 77;
//	}

//	public function testBadDateClass()
//	{
//		$d          = new TypedDate();
//		$c          = new stdClass();
//		$c->aMember = 'string data';
//		$d->date    = $c;
//	}

	public function testNested()
	{
		$n          = new Nested();
		$n->d->date = '2/2/15';

		$this->assertEquals('2015-01-01', $n->date->format('Y-m-d'));
		$this->assertEquals('20150202', $n->d->date->format('Ymd'));
	}

	public function testRange()
	{
		$dr        = new DateRange();
		$dr->start = '20150202';
		$dr->end   = '20150101';
		$this->assertEquals('2015-01-01', $dr->start->format('Y-m-d'));
	}

}


////////////////////////////////////////////////////////////////////////////////////////////////////


class TypedDate extends TypedClass
{
	protected DateTime $date;
}

class Nested extends TypedClass
{
	protected string $name = 'secret';

	protected TypedDate $d;

	protected DateTime $date;

	public function __construct($in = null)
	{
		$this->date = new DateTime('Jan 1, 2015');

		parent::__construct($in);
	}
}

class DateRange extends TypedClass
{
	protected DateTime $start;

	protected DateTime $end;

	public function _checkRelatedProperties()
	{
		if ($this->start > $this->end) {
			$this->start = clone $this->end;
		}
	}
}
