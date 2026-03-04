<?php

use Diskerror\Typed\Scalar\TString;
use Diskerror\Typed\Scalar\TStringTrim;
use Diskerror\Typed\Scalar\TStringNormalize;
use Diskerror\Typed\Scalar\TInteger;
use Diskerror\Typed\Scalar\TIntegerUnsigned;
use Diskerror\Typed\Scalar\TFloat;
use Diskerror\Typed\Scalar\TBoolean;
use Diskerror\Typed\Scalar\TAnything;
use PHPUnit\Framework\TestCase;

class ScalarTest extends TestCase
{
    // === TString ===

    public function testTStringBasic()
    {
        $s = new TString('hello');
        $this->assertSame('hello', $s->get());
    }

    public function testTStringFromInt()
    {
        $s = new TString(42);
        $this->assertSame('42', $s->get());
    }

    public function testTStringFromArray()
    {
        $s = new TString();
        $s->set(['a', 'b']);
        $this->assertSame('["a","b"]', $s->get());
    }

    public function testTStringNullNotNullable()
    {
        $s = new TString(null, false);
        $this->assertSame('', $s->get());
    }

    public function testTStringNullNullable()
    {
        $s = new TString(null, true);
        $this->assertNull($s->get());
    }

    public function testTStringToString()
    {
        $s = new TString('test');
        $this->assertSame('test', (string)$s);
    }

    // === TStringTrim ===

    public function testTStringTrim()
    {
        $s = new TStringTrim('  hello  ');
        $this->assertSame('hello', $s->get());
    }

    public function testTStringTrimNull()
    {
        $s = new TStringTrim(null, true);
        $this->assertNull($s->get());
    }

    // === TStringNormalize ===

    public function testTStringNormalize()
    {
        $s = new TStringNormalize("  hello   world  ");
        // After trim and normalize, multiple spaces become single
        $this->assertSame('hello world', $s->get());
    }

    // === TInteger ===

    public function testTIntegerBasic()
    {
        $i = new TInteger(42);
        $this->assertSame(42, $i->get());
    }

    public function testTIntegerFromString()
    {
        $i = new TInteger('123');
        $this->assertSame(123, $i->get());
    }

    public function testTIntegerFromHexString()
    {
        $i = new TInteger('0xFF');
        $this->assertSame(255, $i->get());
    }

    public function testTIntegerFromEmptyString()
    {
        $i = new TInteger('');
        $this->assertSame(0, $i->get());
    }

    public function testTIntegerFromEmptyStringNullable()
    {
        $i = new TInteger('', true);
        $this->assertNull($i->get());
    }

    public function testTIntegerFromNullString()
    {
        $i = new TInteger('null');
        $this->assertSame(0, $i->get());
    }

    public function testTIntegerFromNanString()
    {
        $i = new TInteger('nan');
        $this->assertSame(0, $i->get());
    }

    public function testTIntegerFromFloat()
    {
        $i = new TInteger(3.7);
        $this->assertSame(3, $i->get());
    }

    public function testTIntegerFromNull()
    {
        $i = new TInteger(null);
        $this->assertSame(0, $i->get());
    }

    public function testTIntegerFromNullNullable()
    {
        $i = new TInteger(null, true);
        $this->assertNull($i->get());
    }

    public function testTIntegerIsset()
    {
        $i = new TInteger(5);
        $this->assertTrue($i->isset());
        $i = new TInteger(null, true);
        $this->assertFalse($i->isset());
    }

    public function testTIntegerUnset()
    {
        $i = new TInteger(42);
        $i->unset();
        $this->assertSame(0, $i->get());

        $i = new TInteger(42, true);
        $i->unset();
        $this->assertNull($i->get());
    }

    // === TIntegerUnsigned ===

    public function testTIntegerUnsignedBasic()
    {
        $i = new TIntegerUnsigned(42);
        $this->assertSame(42, $i->get());
    }

    public function testTIntegerUnsignedNegative()
    {
        $i = new TIntegerUnsigned(-5);
        $this->assertSame(0, $i->get());
    }

    public function testTIntegerUnsignedFromNegativeString()
    {
        $i = new TIntegerUnsigned('-10');
        $this->assertSame(0, $i->get());
    }

    // === TFloat ===

    public function testTFloatBasic()
    {
        $f = new TFloat(3.14);
        $this->assertSame(3.14, $f->get());
    }

    public function testTFloatFromString()
    {
        $f = new TFloat('123.45');
        $this->assertSame(123.45, $f->get());
    }

    public function testTFloatFromEuropeanFormat()
    {
        // Comma as decimal separator
        $f = new TFloat('1.234,56');
        $this->assertSame(1234.56, $f->get());
    }

    public function testTFloatFromEmptyString()
    {
        $f = new TFloat('');
        $this->assertSame(0.0, $f->get());
    }

    public function testTFloatFromNull()
    {
        $f = new TFloat(null);
        $this->assertSame(0.0, $f->get());
    }

    public function testTFloatFromNullNullable()
    {
        $f = new TFloat(null, true);
        $this->assertNull($f->get());
    }

    public function testTFloatFromNullString()
    {
        $f = new TFloat('null');
        $this->assertSame(0.0, $f->get());
    }

    // === TBoolean ===

    public function testTBooleanBasic()
    {
        $b = new TBoolean(true);
        $this->assertTrue($b->get());
        $b = new TBoolean(false);
        $this->assertFalse($b->get());
    }

    public function testTBooleanFromNull()
    {
        $b = new TBoolean(null);
        $this->assertFalse($b->get());
    }

    public function testTBooleanFromNullNullable()
    {
        $b = new TBoolean(null, true);
        $this->assertNull($b->get());
    }

    public function testTBooleanToString()
    {
        $b = new TBoolean(true);
        $this->assertSame('true', (string)$b);
        $b = new TBoolean(false);
        $this->assertSame('false', (string)$b);
        $b = new TBoolean(null, true);
        $this->assertSame('null', (string)$b);
    }

    // === TAnything ===

    public function testTAnythingString()
    {
        $a = new TAnything('hello');
        $this->assertSame('hello', $a->get());
    }

    public function testTAnythingInt()
    {
        $a = new TAnything(42);
        $this->assertSame(42, $a->get());
    }

    public function testTAnythingArray()
    {
        $a = new TAnything(['a', 'b']);
        $this->assertSame('["a","b"]', $a->get());
    }

    public function testTAnythingNull()
    {
        $a = new TAnything(null);
        $this->assertFalse($a->get());
    }

    public function testTAnythingNullNullable()
    {
        $a = new TAnything(null, true);
        $this->assertNull($a->get());
    }
}
