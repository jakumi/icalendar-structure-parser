<?php

namespace jakumi\Parser\iCalendarStructure;

class Event extends Component {
    static $atmostonce = [
        'DTSTAMP',     // rfc 5545
        'UID',         // rfc 5545
        'DTSTART',     // rfc 5545
        'CLASS',       // rfc 5545
        'CREATED',     // rfc 5545
        'DESCRIPTION', // rfc 5545
        'GEO',         // rfc 5545
        'LASTMOD',     // rfc 5545
        'LOCATION',    // rfc 5545
        'ORGANIZER',   // rfc 5545
        'PRIORITY',    // rfc 5545
        'SEQ',         // rfc 5545
        'STATUS',      // rfc 5545
        'SUMMARY',     // rfc 5545
        'TRANSP',      // rfc 5545
        'URL',         // rfc 5545
        'RECURID',     // rfc 5545

        'COLOR',       // rfc 7986
    ];
}