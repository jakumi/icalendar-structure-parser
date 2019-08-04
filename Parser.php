<?php

namespace jakumi\Parser\iCalendarStructure;

/**
 *  a purely syntactical parser according to
 *  https://tools.ietf.org/html/rfc5545 there is no checking for
 *  actual correctness, just some very very basic stuff. no values are
 *  interpreted in any way, it's all just parameters. the only
 *  semantics used is the cardinality of properties. (on the __get
 *  magic methods of the component, which is only for convenience)
 */
class Parser {
    protected $handle = null;

    static $classMap = [
        'VCALENDAR' => Calendar::class,
        'VEVENT' => Event::class,
        'VTODO' => ToDo::class,
        'VJOURNAL' => Journal::class,
        'VFREEBUSY' => FreeBusy::class,
        'VTIMEZONE' => TimeZone::class,
        'VALARM' => Alarm::class,
    ];

    /**
     *  @var []Component
     */
    protected $stack = [];

    function __construct(string $filename) {
        $this->reader = new UnfoldingReader($filename);
    }

    function parse() {
        return $this->parseComponent();
    }

    /**
     *  @return Event|ToDo|Journal|FreeBusy|TimeZone|Alarm|null
     */
    function parseComponent($returnOnSubComponent=false) :?Component {
        $line = trim($this->reader->read());
        if(!preg_match('#^BEGIN:([a-z0-9-]+)$#si', $line, $matches)) {
            $this->reader->pushback($line);
            return null;
        }
        $component = $matches[1];
        $class = static::$classMap[strtoupper($component)] ?? Component::class;

        $properties = [];
        $subcomponents = [];
        while($line = trim($this->reader->read())) {
            if($line == 'END:'.$component) {
                break;
            }
            $property = $this->parseProperty($line);
            if(strpos($property->name, 'BEGIN') === 0) {
                $this->reader->pushback($line);
                $property = null; // is not a property!

                if($returnOnSubComponent) {
                    $component = new $class($component, $properties, $subcomponents);
                    array_unshift($this->stack, $component);
                    return $component;
                }
                $subcomponents[] = $this->parseComponent();
            }
            if(!is_null($property)) {
                $properties[] = $property;
            }
        }

        return new $class($component, $properties, $subcomponents);
    }

    /**
     *  if the next line is "END:[Component]", the next element is
     *  taken from the stack (checked, if it's the right component
     *  name) and returned. otherwise nothing is returned
     *  @return Component|null
     */
    function backtrack(array $subcomponents=[]) {
        $line = trim($this->reader->read());
        if(strtoupper($line) == 'END:'.strtoupper(reset($this->stack)->type)) {
            $component = array_shift($this->stack);
            $component->subcomponents = $subcomponents;
            return $component;
        } else {
            $this->reader->pushback($line);
        }
    }

    /**
     *  @var []Event|null
     */
    var $known = null;

    static function _fetch_name($line) :array {
        preg_match('#^[-a-z0-9]+#siu', $line, $match);
        return [mb_substr($line, mb_strlen($match[0])), $match[0]];
    }
    static function _fetch_char($line) :array {
        return [mb_substr($line, 1), $line[0]];
    }
    static function _fetch_parameter_value($line) :array {
        preg_match('#^"[^\x00-\x08"]*"|^[^\x00-\x08";:,]*#siu', $line, $match);
        return [mb_substr($line, mb_strlen($match[0])), $match[0]];
    }
    static function _fetch_value($line) :array {
        preg_match('#^[^\x00-\x08\x0a-\x1f\x7f]+#siu', $line, $match);
        return [mb_substr($line, mb_strlen($match[0])), $match[0]];
    }

    /**
     *  returns a property from a data line. it'll return also a
     *  property if encountering "BEGIN:VEVENT" or similar. (name:
     *  "BEGIN", value: "VEVENT" in this example). if you use this
     *  function, you have to use this information to parse the
     *  component (or sub-components)
     *
     *  @return Property
     */
    function parseProperty(string $line) :Property {
        list($line, $name) = static::_fetch_name($line);
        list($line, $delim) = static::_fetch_char($line);
        $parameters = [];
        while(true) {
            if($delim == ':') {
                break;
            }

            if($delim != ';') {
                trigger_error('unexpected char: '.$delim, E_USER_ERROR);
            }

            list($line, $parameter_name) = static::_fetch_name($line);

            list($line, $equalsign) = static::_fetch_char($line);
            if($equalsign != '=') {
                trigger_error('unexpected char: '.$equalsign, E_USER_ERROR);
            }

            $parameter_values = [];
            do {
                list($line, $parameter_value) = static::_fetch_parameter_value($line);
                $parameter_values[] = $parameter_value;
                list($line, $delim) = static::_fetch_char($line);
            } while ($delim == ',');
            $parameters[] = new Parameter($parameter_name, $parameter_values);
        }
        // delim is ':'
        list($line, $value) = static::_fetch_value($line);
        if($line) {
            trigger_error('line should be finished, but found rest: '.$line, E_USER_ERROR);
        }

        $property = new Property($name, $value, $parameters);
        return $property;
    }
}
