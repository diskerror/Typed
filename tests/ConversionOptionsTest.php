<?php

use Diskerror\Typed\ConversionOptions;
use Diskerror\Typed\BitWise;
use PHPUnit\Framework\TestCase;

class ConversionOptionsTest extends TestCase
{
    public function testDefaults()
    {
        $co = new ConversionOptions();
        $this->assertSame(0, $co->get());
        $this->assertFalse((bool)$co->isset(ConversionOptions::OMIT_EMPTY));
    }

    public function testSetAndGet()
    {
        $co = new ConversionOptions(ConversionOptions::OMIT_EMPTY | ConversionOptions::DATE_TO_STRING);
        $this->assertTrue((bool)$co->isset(ConversionOptions::OMIT_EMPTY));
        $this->assertTrue((bool)$co->isset(ConversionOptions::DATE_TO_STRING));
        $this->assertFalse((bool)$co->isset(ConversionOptions::OMIT_RESOURCE));
    }

    public function testAdd()
    {
        $co = new ConversionOptions();
        $co->add(ConversionOptions::OMIT_EMPTY);
        $this->assertTrue((bool)$co->isset(ConversionOptions::OMIT_EMPTY));
    }

    public function testUnsetBit()
    {
        $co = new ConversionOptions(ConversionOptions::OMIT_EMPTY | ConversionOptions::DATE_TO_STRING);
        $co->unset(ConversionOptions::OMIT_EMPTY);
        $this->assertFalse((bool)$co->isset(ConversionOptions::OMIT_EMPTY));
        $this->assertTrue((bool)$co->isset(ConversionOptions::DATE_TO_STRING));
    }

    public function testUnsetAll()
    {
        $co = new ConversionOptions(ConversionOptions::OMIT_EMPTY | ConversionOptions::DATE_TO_STRING);
        $co->unset();
        $this->assertSame(0, $co->get());
    }

    // === BitWise ===

    public function testBitWiseSet()
    {
        $bw = new BitWise();
        $bw->set(5);
        $this->assertSame(5, $bw->get());
    }
}
