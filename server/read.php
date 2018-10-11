<?php


$res  =  swoole_async_readfile(__DIR__."/1.txt",function ($filename , $content){
        echo $filename.PHP_EOL;
        echo $content.PHP_EOL;
});

var_dump($res);

echo "start".PHP_EOL;