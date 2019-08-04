<?php

namespace jakumi\Parser\iCalendarStructure;

class TimeZone extends Component {
    static $atmostonce = [
        'TZID',        // rfc 5545
        'LAST-MOD',    // rfc 5545
        'TZURL',       // rfc 5545
    ];
}
