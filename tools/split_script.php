<?php

namespace jakumi\Parser\iCalendarStructure\tools;

use jakumi\Parser\iCalendarStructure\Parser;
use jakumi\Parser\iCalendarStructure\FoldingWriter;

if (PHP_SAPI === 'cli') {
    require_once __DIR__.'/../vendor/autoload.php';


    if($argc < 4) {
        die('insufficient parameters. Usage: php split_script.php input.ics target1.ics target2.ics'.PHP_EOL);
    }

    $source = realpath($argv[1]);
    if($source === false) {
        $source = realpath(dirname($argv[1])).'/'.basename($argv[1]);
    }
    $target1 = realpath($argv[2]);
    if($target1 === false) {
        $target1 = realpath(dirname($argv[2])).'/'.basename($argv[2]);
    }
    $target2 = realpath($argv[3]);
    if($target2 === false) {
        $target2 = realpath(dirname($argv[3])).'/'.basename($argv[3]);
    }

    if($source == $target1 || $source == $target2 || $target1 == $target2) {
        die('the three parameters can\'t reference/be the same file. got:'.PHP_EOL.implode(PHP_EOL, [
            $source,
            $target1,
            $target2,
        ]));
    }

    $split = new Split(new Parser($source));
    $split->splitBy(
        new FoldingWriter($target1),
        new FoldingWriter($target2)
    );
}
