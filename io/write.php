<?php
//swoole_async_writefile(__DIR__ . "/1.txt","xixi",function ($filename){
//
//    swoole_async_readfile($filename,function ($filename,$content){
//            echo $content.PHP_EOL;
//    });
//
//},FILE_APPEND);



swoole_async_write(__DIR__."/1.txt","marun".PHP_EOL,-1,function ($filename){
        swoole_async_readfile($filename,function ($filename,$content){
                echo $content.PHP_EOL;
        });
});
