<?php

namespace jakumi\Parser\iCalendarStructure;

class Property {
    /**
     *  @var string
     */
    var $name;
    /**
     *  @var string
     */
    var $value;
    /**
     *  @var []Parameter
     */
    var $parameters;

    function __construct(string $name, string $value, array $parameters) {
        $this->name = $name;
        $this->value = $value;
        $this->parameters = $parameters;
    }

    /**
     *  @return []Parameter
     */
    function get(string $parameterName) :array {
        $ret = [];
        foreach($this->parameters as $parameter) {
            if($parameter->name == $parameterName) {
                $ret[] = $parameter;
            }
        }
        return $ret;
    }

    /**
     *  @return Parameter|null
     */
    function getOne(string $parameterName) :?Parameter {
        foreach($this->parameters as $parameter) {
            if($parameter->name == $parameterName) {
                return $parameter;
            }
        }
    }

    function __toString() {
        $keypart = [$this->name];
        foreach($this->parameters as $parameter) {
            $keypart[] = (string)$parameter;
        }
        return implode(';', $keypart).':"'.$this->value.'"';
    }
}