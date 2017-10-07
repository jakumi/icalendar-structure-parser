<?php

namespace jakumi\Parser\iCalendarStructure;

/**
 *  this reader's main purpose is to read the iCalendar data format
 *  line by line (because it's a line-based format), while unfolding
 *  at the same time (which is, merging lines which are separated by
 *  CRLF and whitespace).
 *
 *  This reader also buffers and can receive lines to be pushed back
 *  "onto the stream". Just for convenience though. Not quite the
 *  parser.
 */
class UnfoldingReader {
    var $handle;
    var $peek = [];
    
    function __construct($filename) {
        $this->handle = fopen($filename, 'r');
    }

    function peek() {
        if(!is_null($this->peek)) {
            $this->peek = $this->read();
        }
        return $this->peek;
    }

    function read() {
        $line = $this->_read();
        while($peek = $this->_read()) {
            if(substr($peek, 0, 1) == ' ' && substr($line,-2) == "\r\n") {
                $line = substr($line, 0, -2) . substr($peek, 1);
            } else {
                $this->pushback($peek);
                break;
            }
        }
        return $line;
    }

    function _read() {
        if(!empty($this->peek)) {
            return array_shift($this->peek);
        }
        return fgets($this->handle, 8192);
    }

    function pushback(string $line) {
        array_unshift($this->peek, $line);
    }
}