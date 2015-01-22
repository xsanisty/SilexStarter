<?php

function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

//echo convert(memory_get_usage(true)).'<br />';
$app = require '../app/bootstrap/start.php';
//echo convert(memory_get_usage(true)).'<br />';
$app->run();
//echo convert(memory_get_usage(true)).'<br />';