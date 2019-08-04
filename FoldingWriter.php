<?php

namespace jakumi\Parser\iCalendarStructure;

class FoldingWriter {

    private $handle;
    protected $options;

    public function __construct($filename, array $options = []) {
        $this->handle = fopen($filename, 'w');
        $this->options = array_merge($this->defaultOptions(), $options);
    }

    public function defaultOptions() {
        return [
            'wrap_column' => 70,
            'line_ending' => chr(13).chr(10),
            'wrap_string' => chr(13).chr(10).chr(32),
        ];
    }

    public function writeLine(string $line) {
        if(strlen(trim($line)) > $this->options['wrap_column']) {
            $parts = str_split(trim($line), $this->options['wrap_column']);
            $this->_writeLine(implode($this->options['wrap_string'], $parts));
        } else {
            $this->_writeLine($line);
        }
    }

    protected function _writeLine(string $line) {
        fwrite($this->handle, $line.$this->options['line_ending']);
    }

    public function close() {
        fclose($this->handle);
    }

    public function writeComponentBegin(Component $component) {
        $this->writeLine('BEGIN:'.$component->type);
    }

    public function writeComponentEnd(Component $component) {
        $this->writeLine('END:'.$component->type);
    }

    public function writeComponentProperties(Component $component) {
        foreach($component->properties as $property) {
            $this->writeProperty($property);
        }
    }

    public function writeComponentComponents(Component $component) {
        foreach($component->components as $subcomponent) {
            $this->writeComponent($subcomponent);
        }
    }

    public function writeProperty(Property $property) {
        $keypart = [$property->name];
        foreach($property->parameters as $parameter) {
            $keypart[] = (string)$parameter;
        }

        $this->writeLine(implode(';', $keypart).':"'.$property->value.'"');
    }

    public function writeComponent(Component $component, bool $close = true) {
        $this->writeComponentBegin($component);
        $this->writeComponentProperties($component);
        $this->writeComponentComponents($component);
        if($close) {
            $this->writeComponentEnd($component);
        }
    }
}
