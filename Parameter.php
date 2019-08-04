<?php

namespace jakumi\Parser\iCalendarStructure;

class Parameter {
    /**
     *  @var string
     */
    var $name;
    /**
     *  @var []string
     */
    var $values;
    
    function __construct(string $name, array $values) {
        $this->name = $name;
        $this->values = $values;
    }

    function __toString() {
        return $this->name.'="'.implode('","', $this->values).'"';
    }
}
