<?php

use Diskerror\Typed\DateTime;
use Diskerror\Typed\Date;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    // === Construction ===

    public function testConstructFromString()
    {
        $dt = new DateTime('2023-06-15 10:30:00');
        $this->assertEquals('2023', $dt->format('Y'));
        $this->assertEquals('06', $dt->format('m'));
        $this->assertEquals('15', $dt->format('d'));
    }

    public function testConstructFromTimestamp()
    {
        $dt = new DateTime(0);
        $this->assertEquals('1970-01-01', $dt->format('Y-m-d'));
    }

    public function testConstructFromFloat()
    {
        $dt = new DateTime(1561431851.34);
        $this->assertEquals('2019', $dt->format('Y'));
    }

    public function testConstructFromNull()
    {
        $before = new \DateTime('now');
        $dt = new DateTime(null);
        $after = new \DateTime('now');
        $this->assertGreaterThanOrEqual($before->getTimestamp(), $dt->getTimestamp());
        $this->assertLessThanOrEqual($after->getTimestamp(), $dt->getTimestamp());
    }

    public function testConstructFromEmptyString()
    {
        $dt = new DateTime('');
        // Empty string should behave like 'now'
        $this->assertEquals(date('Y'), $dt->format('Y'));
    }

    public function testConstructFromDateTimeInterface()
    {
        $orig = new \DateTime('2020-03-15 08:30:00', new \DateTimeZone('America/New_York'));
        $dt = new DateTime($orig);
        $this->assertEquals($orig->format('Y-m-d H:i:s'), $dt->format('Y-m-d H:i:s'));
        $this->assertEquals($orig->getTimezone()->getName(), $dt->getTimezone()->getName());
    }

    public function testConstructFromArray()
    {
        $dt = new DateTime(['year' => 2023, 'month' => 6, 'day' => 15, 'hour' => 10, 'minute' => 30, 'second' => 0]);
        $this->assertEquals('2023-06-15', $dt->format('Y-m-d'));
        $this->assertEquals('10:30:00', $dt->format('H:i:s'));
    }

    public function testConstructFromAtTimestamp()
    {
        $dt = new DateTime('@1561431851.34');
        $this->assertEquals('2019', $dt->format('Y'));
    }

    public function testBadTypeThrows()
    {
        $this->expectException(InvalidArgumentException::class);
        new DateTime(true);
    }

    // === setDate ===

    public function testSetDateFromArray()
    {
        $dt = new DateTime('2020-01-01');
        $dt->setDate(['year' => 2023, 'month' => 12]);
        $this->assertEquals('2023', $dt->format('Y'));
        $this->assertEquals('12', $dt->format('m'));
        // Day should remain from current (01)
        $this->assertEquals('01', $dt->format('d'));
    }

    public function testSetDateFromDateTimeInterface()
    {
        $dt = new DateTime('2020-01-01');
        $source = new \DateTime('2023-06-15');
        $dt->setDate($source);
        $this->assertEquals('2023-06-15', $dt->format('Y-m-d'));
    }

    // === setTime ===

    public function testSetTimeFromDateTimeInterface()
    {
        $dt = new DateTime('2020-01-01 00:00:00');
        $source = new \DateTime('2023-06-15 14:35:42.123456');
        $dt->setTime($source);

        // Expected (correct) behavior:
        // hour=14, minute=35, second=42, microsecond=123456

        $this->assertEquals(14, (int)$dt->format('G'), 'Hour does not match');
        $this->assertEquals(35, (int)$dt->format('i'), 'Minutes does not match');
        $this->assertEquals(42, (int)$dt->format('s'), 'Seconds does not match');
    }

    public function testSetTimeFromArray()
    {
        $dt = new DateTime('2020-01-01 00:00:00');
        $dt->setTime(['hour' => 14, 'minute' => 30, 'second' => 45]);
        $this->assertEquals('14:30:45', $dt->format('H:i:s'));
    }

    public function testSetTimePartialArray()
    {
        $dt = new DateTime('2020-01-01 10:20:30');
        $dt->setTime(['hour' => 5]);
        $this->assertEquals('05:20:30', $dt->format('H:i:s'));
    }

    // === __toString ===

    public function testToString()
    {
        $dt = new DateTime('2023-06-15 10:30:00.123456');
        $str = (string)$dt;
        $this->assertStringContainsString('2023-06-15', $str);
        $this->assertStringContainsString('10:30:00', $str);
    }

    // === jsonSerialize ===

    public function testJsonSerialize()
    {
        $dt = new DateTime('2023-06-15 10:30:00.123456', new \DateTimeZone('UTC'));
        $json = $dt->jsonSerialize();
        $this->assertStringContainsString('2023-06-15', $json);
        $this->assertStringContainsString('T10:30:00.123456', $json);
    }

    // === getTimestampMilli / getTimestampWithDecimal ===

    public function testGetTimestampMilli()
    {
        $dt = new DateTime('@1561431851.340');
        $this->assertEquals(1561431851340, $dt->getTimestampMilli());
    }

    public function testGetTimestampWithDecimal()
    {
        $dt = new DateTime('@1561431851.340');
        $this->assertEqualsWithDelta(1561431851.34, $dt->getTimestampWithDecimal(), 0.001);
    }

    // ==================== Date class ====================

    public function testDateConstruction()
    {
        $d = new Date('2023-06-15');
        $this->assertEquals('2023-06-15', (string)$d);
        // Time should be noon
        $this->assertEquals('12', $d->format('H'));
    }

    public function testDateSetTimeThrows()
    {
        $this->expectException(LogicException::class);
        $d = new Date('2023-06-15');
        $d->setTime(10, 30);
    }

    public function testDateAdd()
    {
        $d = new Date('2023-06-15');
        $d->add(new DateInterval('P1D'));
        $this->assertEquals('2023-06-16', (string)$d);
        // Time should still be noon
        $this->assertEquals('12', $d->format('H'));
    }

    public function testDateSub()
    {
        $d = new Date('2023-06-15');
        $d->sub(new DateInterval('P1D'));
        $this->assertEquals('2023-06-14', (string)$d);
    }

    public function testDateJsonSerialize()
    {
        $d = new Date('2023-06-15');
        $json = json_encode($d);
        $this->assertEquals('"2023-06-15"', $json);
    }
}
