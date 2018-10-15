<?php


//$res  =  swoole_async_readfile(__DIR__ . "/1.txt",function ($filename , $content){
//        echo $filename.PHP_EOL;
//        echo $content.PHP_EOL;
//});

swoole_async_read(__DIR__."/1.txt",function ($filename,$content){
        echo $content.PHP_EOL;
},3,0);

//var_dump($res);
//
//echo "start".PHP_EOL;