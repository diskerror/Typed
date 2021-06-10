<?php /** @noinspection ALL */
declare(strict_types = 1);

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

	/**
	 * @expectedException            Exception
	 * @expectedExceptionMessage    DateTime::__construct(): Failed to parse time string (77) at position 0 (7):
	 *                              Unexpected character
	 */
	public function testBadDateValue()
	{
		$this->expectException(Exception::class);
		$this->expectExceptionMessage("DateTime::__construct(): Failed to parse time string (77) at position 0 (7): Unexpected character");

		$d = new TypedDate();
		$d->date = 77;
	}

//	/**
//	 * @expectedException            TypeError
//	 * @expectedExceptionMessage       DateTime::__construct() expects parameter 1 to be string, object given
//	 */
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
	protected $date = [DateTime::class];
}

class Nested extends TypedClass
{
	protected $name = 'secret';

	protected $d = [TypedDate::class];

	protected $date = [DateTime::class, 'Jan 1, 2015'];
}

class DateRange extends \Diskerror\Typed\TypedClass
{
	protected $start = [DateTime::class];

	protected $end = [DateTime::class];

	protected function _checkRelatedProperties()
	{
		if ($this->start > $this->end) {
			$this->start = clone $this->end;
		}
	}
}
