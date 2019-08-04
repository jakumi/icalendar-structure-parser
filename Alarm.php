<?php

namespace jakumi\Parser\iCalendarStructure;

class Alarm extends Component {
    static $atmostonce = [
        'ACTION',      // rfc 5545
        'DESCRIPTION', // rfc 5545
        'TRIGGER',     // rfc 5545
        'SUMMARY',     // rfc 5545
        'DURATION',    // rfc 5545
        'REPEAT',      // rfc 5545
    ];
}