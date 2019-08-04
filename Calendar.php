<?php

namespace jakumi\Parser\iCalendarStructure;

class Calendar extends Component {
    static $atmostonce = [
        'PRODID',   // rfc 5545
        'VERSION',  // rfc 5545
        'CALSCALE', // rfc 5545
        'METHOD',   // rfc 5545
        
        'UID',      // rfc 7986
        'LAST-MOD', // rfc 7986
        'URL',      // rfc 7986
        'REFRESH',  // rfc 7986
        'SOURCE',   // rfc 7986
        'COLOR',    // rfc 7986
    ];
}
