<?php

namespace jakumi\Parser\iCalendarStructure;

class Component {
    /**
     *  @var []Property
     */
    var $properties = [];
    /**
     *  @var []Component
     */
    var $components = [];
    /**
     *  @var string
     */
    var $type;

    static $atmostonce = [];
    
    function __construct(string $type, array $properties, array $subcomponents) {
        $this->type = $type;
        $this->properties = $properties;
        $this->subcomponents = $subcomponents;
    }

    function __get($key) {
        if(in_array(strtoupper($key), static::$atmostonce)) {
            return $this->getFirst($key);
        } else {
            return $this->get($key);
        }
    }
    
    /**
     *  @return []Property
     */
    function get(string $propertyName) :array {
        $ret = [];
        foreach($this->properties as $property) {
            if($property->name == $propertyName) {
                $ret[] = $property;
            }
        }
        return $ret;
    }

    /**
     *  @return Property|null
     */
    function getFirst(string $propertyName) :?Property {
        foreach($this->properties as $property) {
            if($property->name == $propertyName) {
                return $property;
            }
        }
    }
}