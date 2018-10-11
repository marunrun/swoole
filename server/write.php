<?php
swoole_async_writefile(__DIR__."/1.txt","xixi",function ($filename){

    swoole_async_readfile($filename,function ($filename,$content){
            echo $content.PHP_EOL;
    });

},$flags = 0);
