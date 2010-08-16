<?php

ini_set("memory_limit", "128M");

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

$count = array(100, 500, 1000, 5000, 10000, 50000, 100000, 500000);

foreach($count as $v) {
    echo "\n## Count: $v\n";

    echo "\n-- Judy1 \n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";

    $s=microtime(true);
    $judy = new Judy1();
    for ($i=0; $i<$v; $i++)
        $judy->set($i);
    var_dump($judy->test(100));
    $judy->unset(102);
    var_dump($judy->test(102));
    echo "Count: ".$judy->count()."\n";
    echo "MU: ".convert($judy->memory_usage())."\n";
    $e=microtime(true);
    echo "Elapsed time: ".($e - $s)." sec.\n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";

    unset($judy);

    echo "\n-- ARRAY \n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";

    $s=microtime(true);
    $array = array();
    for ($i=0; $i<$v; $i++)
        $array[$i] = true;
    unset($array[102]);
    var_dump($array[100]);
    var_dump($array[102]);
    $e=microtime(true);
    echo "Count: ".count($array)."\n";
    echo "Elapsed time: ".($e - $s)." sec.\n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";

    unset($array);

    echo "\n-- SplDoublyLinkedList \n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";

    $s=microtime(true);
    $spl = new SplDoublyLinkedList();
    for ($i=0; $i<$v; $i++)
        $spl->push($i);
    try {
        $spl->offsetUnset(102);
        var_dump($spl->offsetGet(100));
        var_dump($spl->offsetGet(102));
    } catch (OutOfRangeException $e) {
        echo $e;
    }
    $e=microtime(true);
    echo "Count: ".$spl->count()."\n";
    echo "Elapsed time: ".($e - $s)." sec.\n";
    echo "Mem usage: ". convert(memory_get_usage()) . "\n";
    echo "Mem real: ". convert(memory_get_usage(true)) . "\n";
    
    try {
        for ($i=0; $i<$v; $i++)
            $spl->pop();
    } catch (Exception $e) {
    }

    unset($spl);

    echo "\n";
}
