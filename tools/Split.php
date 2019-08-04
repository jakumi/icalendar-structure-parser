<?php

namespace jakumi\Parser\iCalendarStructure\tools;

use jakumi\Parser\iCalendarStructure\Parser;
use jakumi\Parser\iCalendarStructure\FoldingWriter;
use jakumi\Parser\iCalendarStructure\Component;
use jakumi\Parser\iCalendarStructure\Property;

class Split {
    /**
     * @var Parser
     */
    protected $parser;

    static $now;

    function __construct(Parser $parser) {
        $this->parser = $parser;
        static::$now = date_create();
    }

    /**
     * function to call to split a calendar
     *
     * the callback gets a Component object and should return 0 (for
     * both files), 1 (for target 1) or 2 (for target 2).
     *
     * Please take care, that the Timezone entries are always written
     * to both targets, if you don't really mind
     */
    function splitBy(FoldingWriter $target1, FoldingWriter $target2, callable $callback = null) {
        $calendar = $this->parser->parseComponent(true);

        $target1->writeComponent($calendar, false);
        $target2->writeComponent($calendar, false);

        while($component = $this->parser->parseComponent()) {

            $targets = $callback ? $callback($component) : static::defaultCallback($component);
            echo $targets;
            switch($targets) {
            case 0:
                $target1->writeComponent($component);
                $target2->writeComponent($component);
                break;
            case 1:
                $target1->writeComponent($component);
                break;
            case 2:
                $target2->writeComponent($component);
                break;
            default:
                throw new \Exception('callback must return either 0, 1 or 2');
            }
        }

        $target1->writeComponentEnd($calendar);
        $target1->close();
        $target2->writeComponentEnd($calendar);
        $target2->close();
    }



    public static function defaultCallback(Component $component) {
        if(in_array($component->type, [
            'VTIMEZONE',
        ])) {
            return 0; // both
        }

        return $component->DTEND instanceof Property && is_string($component->DTEND->value)
            ? ((date_create($component->DTEND->value) > static::$now) ? 2 : 1)
            : 0;
    }
}
