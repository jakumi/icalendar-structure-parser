<?php

namespace jakumi\Parser\iCalendarStructure;

class FreeBusy extends Component {
    static $atmostonce = [
        'DTSTAMP',     // rfc 5545
        'UID',         // rfc 5545
        'CONTACT',     // rfc 5545
        'DTSTART',     // rfc 5545
        'DTEND',       // rfc 5545
        'ORGANIZER',   // rfc 5545
        'URL',         // rfc 5545
    ];
}