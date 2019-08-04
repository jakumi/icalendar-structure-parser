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

    function __construct(string $type, array $properties, array $components) {
        $this->type = $type;
        $this->properties = $properties;
        $this->components = $components;
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
            if(strtoupper($property->name) == strtoupper($propertyName)) {
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
            if(strtoupper($property->name) == strtoupper($propertyName)) {
                return $property;
            }
        }
        return null;
    }

    function __toString() {
        $ret = '';
        $ret .= 'BEGIN:'.$this->type.PHP_EOL;
        foreach($this->properties as $key => $property) {
            $ret .= $property->__toString();
        }
        foreach($this->components as $component) {
            $ret .= $component->__toString();
        }
        $ret .= 'END:'.$this->type.PHP_EOL;
        return $ret;
    }
}
