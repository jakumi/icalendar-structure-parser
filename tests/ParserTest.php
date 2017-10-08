<?php

namespace jakumi\Parser\iCalendarStructure\tests;

use jakumi\Parser\iCalendarStructure\Parser;
use jakumi\Parser\iCalendarStructure\Calendar;
use jakumi\Parser\iCalendarStructure\Event;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase {

    var $parsed1;
    
    function testParseTest1() {
        $ics = new Parser(__DIR__.'/test1.ics');
        $cal = $ics->parse();
        $this->assertNotEmpty($cal);
        $this->assertInstanceOf(Calendar::class, $cal);

        return $cal;
    }

    /**
     *  @depends testParseTest1
     */
    function testParseProdid($cal) {
        $this->assertNotEmpty($cal->getFirst('prodid'));
        $this->assertEquals("-//xyz Corp//NONSGML PDA Calendar Version 1.0//EN", $cal->getFirst('prodid')->value);
    }

    /**
     *  @depends testParseTest1
     */
    function testParseVersion($cal) {
        $this->assertNotEmpty($cal->getFirst('version'));
        $this->assertEquals("2.0", $cal->getFirst('version')->value);
    }

    /**
     *  @depends testParseTest1
     */
    function testEvent1($cal) {
        $this->assertInternalType('array', $cal->components);
        $this->assertCount(1, $cal->components);
        return reset($cal->components);
    }

    /**
     *  @depends testEvent1
     */
    function testEventUid($event) {
        $this->assertNotEmpty($event->uid);
    }

    function testParsePartial() {
        $ics = new Parser(__DIR__.'/test1.ics');
        $calendar = $ics->parseComponent(true);
        $this->assertInstanceOf(Calendar::class, $calendar);
        $element = $ics->parseComponent();
        $this->assertInstanceOf(Event::class, $element);
        $this->assertEmpty($ics->parseComponent());
        $calendar2 = $ics->backtrack([$element]);
        $this->assertEquals($calendar, $calendar2);
    }
}