<?php

namespace jakumi\Parser\iCalendarStructure\tests;

function loader($classname) {
    if(strpos($classname, 'jakumi\Parser\iCalendarStructure') !== 0) {
        return;
    }
    $final_classname = str_replace("jakumi\\Parser\\iCalendarStructure\\", '', $classname);
    
    if(strpos($classname, 'jakumi\Parser\iCalendarStructure\tests') === 0) {
        include(__DIR__.'/'.str_replace("jakumi\\Parser\\iCalendarStructure\\tests\\", '', $classname).'.php');
    } else {
        include(__DIR__.'/../'.$final_classname.'.php');
    }
}

spl_autoload_register(__NAMESPACE__.'\loader');
