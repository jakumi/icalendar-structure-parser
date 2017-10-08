<?php

namespace jakumi\Parser\iCalendarStructure\tests;

use jakumi\Parser\iCalendarStructure\UnfoldingReader;
use PHPUnit\Framework\TestCase;

class UnfoldingReaderTest extends TestCase {
    function testCanReadSimpleCalendar() {
        $uf = new UnfoldingReader(__DIR__.'/test1.ics');
        $this->assertEquals("BEGIN:VCALENDAR", trim($uf->read()));
        $this->assertEquals("PRODID:-//xyz Corp//NONSGML PDA Calendar Version 1.0//EN", trim($uf->read()));
        $this->assertEquals("VERSION:2.0", trim($uf->read()));
        $this->assertEquals("BEGIN:VEVENT", trim($uf->read()));
        $this->assertEquals("DTSTAMP:19960704T120000Z", trim($uf->read()));
        $this->assertEquals("UID:uid1@example.com", trim($uf->read()));
        $this->assertEquals("ORGANIZER:mailto:jsmith@example.com", trim($uf->read()));
        $this->assertEquals("DTSTART:19960918T143000Z", trim($uf->read()));
        $this->assertEquals("DTEND:19960920T220000Z", trim($uf->read()));
        $this->assertEquals("STATUS:CONFIRMED", trim($uf->read()));
        $this->assertEquals("CATEGORIES:CONFERENCE", trim($uf->read()));
        $this->assertEquals("SUMMARY:Networld+Interop Conference", trim($uf->read()));
        // folding test
        $this->assertEquals("DESCRIPTION:Networld+Interop Conference and Exhibit\\nAtlanta World Congress Center\\nAtlanta\\, Georgia", trim($uf->read()));
        $this->assertEquals("END:VEVENT", trim($uf->read()));
        $this->assertEquals("END:VCALENDAR", trim($uf->read()));
    }
}